<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Models\AIAssistantUpload;
use App\Jobs\ProcessUploadedDocument;
use App\Services\AIService;
use App\Models\Document\Document;
use App\Models\Banking\Transaction;
use App\Models\Setting\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AIAssistant extends Controller
{
    /**
     * Skip ACL permissions for the AI Assistant as it's available to all authenticated admins.
     */
    public function assignPermissionsToController()
    {
        // Do nothing to skip permission assignment
    }
    /**
     * Display the AI Assistant workspace.
     */
    public function index()
    {
        $companyId = company_id();

        // Get past uploads history
        $uploads = AIAssistantUpload::where('company_id', $companyId)
            ->with(['document', 'transaction'])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        return view('common.ai_assistant.index', compact('uploads'));
    }

    /**
     * Handle document receipt/invoice file upload.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('document');
            $companyId = company_id();

            // Store file securely
            $path = $file->store("ai_uploads/{$companyId}");
            $absolutePath = storage_path("app/uploads/{$path}");

            // Create record
            $upload = AIAssistantUpload::create([
                'company_id' => $companyId,
                'file_path' => $absolutePath,
                'status' => 'uploaded',
            ]);

            // Dispatch parsing and bookkeeping in background
            $this->dispatch(new ProcessUploadedDocument($upload));

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully! AI is starting to parse it...',
                'data' => $upload
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to upload document to AI assistant: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time status updates of uploads.
     */
    public function history()
    {
        $companyId = company_id();

        $uploads = AIAssistantUpload::where('company_id', $companyId)
            ->with(['document', 'transaction'])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $uploads
        ]);
    }

    /**
     * Generate real-time natural language answers using accounting contextual data.
     */
    public function insights(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        try {
            $companyId = company_id();
            $question = $request->input('question');

            // Gather context data from database to feed the LLM
            $totalInvoices = Document::invoice()->where('company_id', $companyId)->sum('amount');
            $paidInvoices = Document::invoice()->where('company_id', $companyId)->where('status', 'paid')->sum('amount');
            $totalBills = Document::bill()->where('company_id', $companyId)->sum('amount');
            $paidBills = Document::bill()->where('company_id', $companyId)->where('status', 'paid')->sum('amount');

            // Query transaction categories summary
            $transactionsSummary = Transaction::where('company_id', $companyId)
                ->selectRaw('type, sum(amount) as total')
                ->groupBy('type')
                ->get();

            $incomeTotal = 0;
            $expenseTotal = 0;
            foreach ($transactionsSummary as $summary) {
                if ($summary->type === 'income') {
                    $incomeTotal = $summary->total;
                } elseif ($summary->type === 'expense') {
                    $expenseTotal = $summary->total;
                }
            }

            // Query Top 5 Expenses for this month
            $topExpenses = Transaction::where('company_id', $companyId)
                ->where('type', 'expense')
                ->whereMonth('paid_at', now()->month)
                ->with('category')
                ->selectRaw('category_id, sum(amount) as total')
                ->groupBy('category_id')
                ->orderByDesc('total')
                ->take(5)
                ->get();
            
            $expenseStr = "";
            foreach ($topExpenses as $expense) {
                $catName = $expense->category ? $expense->category->name : 'Uncategorized';
                $expenseStr .= "  * {$catName}: " . money($expense->total)->format() . "\n";
            }

            // Query Overdue Invoices
            $overdueInvoices = Document::invoice()
                ->where('company_id', $companyId)
                ->where('status', 'overdue')
                ->sum('amount');

            // Query 5 Most Recent Transactions
            $recentTransactions = Transaction::where('company_id', $companyId)
                ->with(['contact', 'category'])
                ->orderBy('paid_at', 'desc')
                ->take(5)
                ->get();
            
            $recentStr = "";
            foreach ($recentTransactions as $tx) {
                $contact = $tx->contact ? $tx->contact->name : 'Unknown';
                $cat = $tx->category ? $tx->category->name : 'Uncategorized';
                $recentStr .= "  * {$tx->paid_at->format('Y-m-d')} | {$tx->type} | " . money($tx->amount)->format() . " | Contact: {$contact} | Cat: {$cat}\n";
            }

            // Build Context Prompt
            $contextPrompt = "Here is the current financial state of the company:
- Total Invoiced (Sales): " . money($totalInvoices)->format() . " (Paid Invoices: " . money($paidInvoices)->format() . ", Overdue: " . money($overdueInvoices)->format() . ")
- Total Billed (Purchases): " . money($totalBills)->format() . " (Paid Bills: " . money($paidBills)->format() . ")
- Direct Income Transactions: " . money($incomeTotal)->format() . "
- Direct Expense Transactions: " . money($expenseTotal)->format() . "
- Net Cash Profit Flow: " . money($incomeTotal - $expenseTotal)->format() . "

Top 5 Expense Categories (This Month):
" . ($expenseStr ?: "  * No expenses recorded this month\n") . "

5 Most Recent Transactions:
" . ($recentStr ?: "  * No recent transactions\n") . "

User Question: \"{$question}\"

Provide a helpful, precise financial analysis and response based on these metrics. Reference specific figures in your analysis.";

            $aiService = new AIService();
            $answer = $aiService->generateFinancialInsights($contextPrompt);

            return response()->json([
                'success' => true,
                'data' => [
                    'answer' => $answer
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to generate financial insights: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'AI is temporarily offline. Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
