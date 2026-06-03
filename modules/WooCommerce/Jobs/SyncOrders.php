<?php

namespace Modules\WooCommerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\WooCommerce\Services\WooCommerceApi;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use Illuminate\Support\Str;

class SyncOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $company_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company_id)
    {
        $this->company_id = $company_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(WooCommerceApi $api)
    {
        $orders = $api->getOrders();

        if (!$orders) {
            return;
        }

        foreach ($orders as $order) {
            $this->processOrder($order);
        }
    }

    protected function processOrder($order)
    {
        // 1. Sync Customer (Contact)
        $customer = $this->syncCustomer($order);

        // 2. Sync Items (Products)
        $items = $this->syncItems($order);

        // 3. Create Invoice (Document)
        $this->createInvoice($order, $customer, $items);
    }

    protected function syncCustomer($order)
    {
        $email = $order['billing']['email'] ?? 'guest@example.com';
        $firstName = $order['billing']['first_name'] ?? 'Guest';
        $lastName = $order['billing']['last_name'] ?? '';

        $contact = Contact::where('company_id', $this->company_id)
            ->where('email', $email)
            ->where('type', 'customer')
            ->first();

        if (!$contact) {
            $contact = Contact::create([
                'company_id' => $this->company_id,
                'type' => 'customer',
                'name' => trim("$firstName $lastName"),
                'email' => $email,
                'phone' => $order['billing']['phone'] ?? null,
                'address' => $order['billing']['address_1'] ?? null,
                'city' => $order['billing']['city'] ?? null,
                'zip_code' => $order['billing']['postcode'] ?? null,
                'state' => $order['billing']['state'] ?? null,
                'country' => $order['billing']['country'] ?? null,
                'currency_code' => setting('default.currency'),
                'enabled' => 1,
            ]);
        }

        return $contact;
    }

    protected function syncItems($order)
    {
        $syncedItems = [];

        foreach ($order['line_items'] as $lineItem) {
            $itemName = $lineItem['name'];
            
            $item = Item::where('company_id', $this->company_id)
                ->where('name', $itemName)
                ->first();

            if (!$item) {
                $item = Item::create([
                    'company_id' => $this->company_id,
                    'name' => $itemName,
                    'description' => "WooCommerce Product ID: {$lineItem['product_id']}",
                    'sale_price' => $lineItem['price'],
                    'purchase_price' => $lineItem['price'],
                    'enabled' => 1,
                ]);
            }

            $syncedItems[] = [
                'model' => $item,
                'qty' => $lineItem['quantity'],
                'price' => $lineItem['price'],
                'total' => $lineItem['total'],
            ];
        }

        return $syncedItems;
    }

    protected function createInvoice($order, $contact, $items)
    {
        $orderId = "WC-" . $order['id'];
        
        $exists = Document::where('company_id', $this->company_id)
            ->where('type', 'invoice')
            ->where('document_number', $orderId)
            ->first();

        if ($exists) {
            return;
        }

        $invoice = Document::create([
            'company_id' => $this->company_id,
            'type' => 'invoice',
            'document_number' => $orderId,
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'contact_email' => $contact->email,
            'issued_at' => substr($order['date_created'], 0, 10),
            'due_at' => substr($order['date_created'], 0, 10),
            'amount' => $order['total'],
            'currency_code' => $order['currency'],
            'currency_rate' => 1,
            'status' => 'paid',
        ]);

        foreach ($items as $syncedItem) {
            $invoice->items()->create([
                'company_id' => $this->company_id,
                'item_id' => $syncedItem['model']->id,
                'name' => $syncedItem['model']->name,
                'quantity' => $syncedItem['qty'],
                'price' => $syncedItem['price'],
                'total' => $syncedItem['total'],
                'currency_code' => $order['currency'],
                'currency_rate' => 1,
            ]);
        }
    }
}
