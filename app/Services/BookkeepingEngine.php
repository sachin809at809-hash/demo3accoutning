<?php

namespace App\Services;

use App\Models\AIAssistantUpload;
use App\Models\Document\Document;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Models\Banking\Account;
use App\Jobs\Common\CreateContact;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class BookkeepingEngine
{
    use DispatchesJobs;

    /**
     * Process extracted JSON data from AI upload and perform bookkeeping.
     *
     * @param AIAssistantUpload $upload
     * @return void
     */
    public function processExtractedData(AIAssistantUpload $upload): void
    {
        $extracted = $upload->extracted_data;
        if (empty($extracted)) {
            throw new \Exception("No extracted JSON data found to process bookkeeping.");
        }

        $companyId = $upload->company_id;
        $docType = $extracted['document_type'] ?? 'receipt';
        $type = ($docType === 'invoice') ? 'invoice' : 'bill';

        Log::info("Bookkeeping Engine started: Company ID {$companyId}, Document Type {$type}");

        // 1. Resolve Currency
        $currencyCode = $extracted['currency_code'] ?? setting('default.currency', 'USD');

        // 2. Find or Create Contact (Vendor or Customer)
        $contactName = $extracted['vendor_name'] ?? 'Unknown Vendor';
        $contactType = ($type === 'invoice') ? 'customer' : 'vendor';

        $contact = Contact::where('company_id', $companyId)
            ->where('type', $contactType)
            ->where('name', 'like', "%{$contactName}%")
            ->first();

        if (!$contact) {
            Log::info("Contact not found. Creating contact: {$contactName} ({$contactType})");
            $contact = $this->dispatch(new CreateContact([
                'company_id' => $companyId,
                'type' => $contactType,
                'name' => $contactName,
                'email' => $extracted['vendor_email'] ?? null,
                'currency_code' => $currencyCode,
                'enabled' => 1
            ]));
        }

        // 3. Find or Create Category
        $categoryName = $extracted['category_suggestion'] ?? 'Miscellaneous';
        $categoryType = ($type === 'invoice') ? 'income' : 'expense';

        $category = Category::where('company_id', $companyId)
            ->where('type', $categoryType)
            ->where('name', 'like', "%{$categoryName}%")
            ->first();

        if (!$category) {
            // Check fallback category of same type
            $category = Category::where('company_id', $companyId)
                ->where('type', $categoryType)
                ->first();

            if (!$category) {
                Log::info("Category not found. Creating category: {$categoryName}");
                $category = Category::create([
                    'company_id' => $companyId,
                    'type' => $categoryType,
                    'name' => $categoryName,
                    'color' => '#6b7280',
                    'enabled' => 1
                ]);
            }
        }

        // 4. Generate Document Number
        $lastDoc = Document::where('type', $type)
            ->where('company_id', $companyId)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastDoc ? ((int) preg_replace('/[^0-9]/', '', $lastDoc->document_number)) + 1 : 1;
        $documentNumber = ($type === 'invoice' ? 'INV-' : 'BILL-') . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // 5. Prepare Document Request Array
        $documentData = [
            'company_id' => $companyId,
            'type' => $type,
            'document_number' => $documentNumber,
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'contact_email' => $contact->email,
            'issued_at' => $extracted['invoice_date'] ?? today()->toDateString(),
            'due_at' => $extracted['due_date'] ?? today()->addDays(30)->toDateString(),
            'amount' => $extracted['total_amount'] ?? 0,
            'currency_code' => $currencyCode,
            'currency_rate' => 1,
            'category_id' => $category->id,
            'status' => 'draft',
            'items' => []
        ];

        // 6. Map Line Items
        if (!empty($extracted['line_items']) && is_array($extracted['line_items'])) {
            foreach ($extracted['line_items'] as $item) {
                $documentData['items'][] = [
                    'name' => $item['name'] ?? 'Line Item',
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? ($extracted['total_amount'] ?? 0),
                    'total' => $item['total'] ?? ($extracted['total_amount'] ?? 0),
                    'currency_code' => $currencyCode,
                ];
            }
        } else {
            // Default single line item
            $documentData['items'][] = [
                'name' => 'General Purchase / Services',
                'quantity' => 1,
                'price' => $extracted['total_amount'] ?? 0,
                'total' => $extracted['total_amount'] ?? 0,
                'currency_code' => $currencyCode,
            ];
        }

        Log::info("Dispatching CreateDocument job for {$documentNumber}");
        $document = $this->dispatch(new CreateDocument($documentData));

        // 7. Auto-Pay if it is a Receipt
        $transaction = null;
        if ($docType === 'receipt') {
            Log::info("Document is a receipt. Automatically creating a payment transaction.");

            // Resolve Default Cash Account
            $account = Account::where('company_id', $companyId)->where('enabled', 1)->first();
            if (!$account) {
                $account = Account::create([
                    'company_id' => $companyId,
                    'name' => 'Cash',
                    'number' => '1000',
                    'currency_code' => $currencyCode,
                    'opening_balance' => 0,
                    'enabled' => 1
                ]);
            }

            $paymentMethod = setting('default.payment_method', 'offline');

            $transactionData = [
                'amount' => $document->amount,
                'account_id' => $account->id,
                'currency_code' => $currencyCode,
                'paid_at' => $document->issued_at,
                'payment_method' => $paymentMethod,
                'type' => 'expense',
                'document_id' => $document->id,
                'contact_id' => $contact->id,
                'category_id' => $category->id,
                'notify' => 0,
            ];

            $transaction = $this->dispatch(new CreateBankingDocumentTransaction($document, $transactionData));
        }

        // 8. Associate uploaded file attachment
        try {
            if (class_exists(\Plank\Mediable\Facades\MediaUploader::class)) {
                $media = \Plank\Mediable\Facades\MediaUploader::importPath('local', $upload->file_path);
                if ($media) {
                    $document->attachMedia($media, 'attachment');
                    Log::info("Successfully attached media to Document {$document->id}");
                }
            }
        } catch (\Exception $e) {
            Log::warning("Failed to attach uploaded file to document: " . $e->getMessage());
        }

        // 9. Update Upload Entry Status
        $upload->update([
            'status' => 'completed',
            'document_type' => $type,
            'document_id' => $document->id,
            'transaction_id' => $transaction ? $transaction->id : null
        ]);

        Log::info("Bookkeeping completed successfully for upload ID {$upload->id}");
    }
}
