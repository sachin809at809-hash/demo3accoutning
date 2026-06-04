<?php

namespace App\Jobs;

use App\Abstracts\Job;
use App\Models\Common\Company;
use App\Models\Auth\User;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Services\AIService;
use App\Mail\WeeklyFinancialInsights;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class GenerateWeeklyFinancialReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info("GenerateWeeklyFinancialReport Job started.");

        $companies = Company::where('enabled', 1)->get();

        foreach ($companies as $company) {
            try {
                $this->generateReportForCompany($company);
            } catch (\Exception $e) {
                Log::error("Failed to generate weekly report for Company {$company->id}: " . $e->getMessage());
            }
        }

        Log::info("GenerateWeeklyFinancialReport Job completed.");
    }

    protected function generateReportForCompany(Company $company)
    {
        $startDate = Carbon::now()->subDays(7)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // 1. Gather 7-day financials
        $income = Transaction::where('company_id', $company->id)
            ->where('type', 'income')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        $expenses = Transaction::where('company_id', $company->id)
            ->where('type', 'expense')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        $newInvoices = Document::invoice()
            ->where('company_id', $company->id)
            ->whereBetween('issued_at', [$startDate, $endDate])
            ->sum('amount');

        // Top Expenses
        $topExpenses = Transaction::where('company_id', $company->id)
            ->where('type', 'expense')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with('category')
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->take(3)
            ->get();
        
        $expenseStr = "";
        foreach ($topExpenses as $expense) {
            $catName = $expense->category ? $expense->category->name : 'Uncategorized';
            $expenseStr .= "  - {$catName}: " . money($expense->total, $company->currency_code)->format() . "\n";
        }

        $contextPrompt = "You are an Executive AI Financial Assistant. Generate a 'Weekly Financial Insights' executive summary for the business owner.
        
Data for the past 7 days:
- Cash Collected (Income): " . money($income, $company->currency_code)->format() . "
- Cash Spent (Expenses): " . money($expenses, $company->currency_code)->format() . "
- Net Cash Flow: " . money($income - $expenses, $company->currency_code)->format() . "
- New Sales Invoiced: " . money($newInvoices, $company->currency_code)->format() . "

Top Expense Categories this week:
" . ($expenseStr ?: "  - None\n") . "

Write a 2-3 paragraph professional narrative analyzing this week's performance. Highlight if cash flow is positive or negative. Do not use markdown blocks, just write paragraphs. Keep it encouraging but realistic.";

        $aiService = new AIService();
        $aiAnalysis = $aiService->generateFinancialInsights($contextPrompt);

        // Parse paragraphs to an array for the email
        $paragraphs = array_filter(array_map('trim', explode("\n\n", $aiAnalysis)));

        $data = [
            'income' => money($income, $company->currency_code)->format(),
            'expenses' => money($expenses, $company->currency_code)->format(),
            'net' => money($income - $expenses, $company->currency_code)->format(),
            'newInvoices' => money($newInvoices, $company->currency_code)->format(),
            'analysis' => $paragraphs,
            'company_name' => $company->name,
        ];

        // Send to all admin users of the company
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->whereHas('companies', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new WeeklyFinancialInsights($data));
            Log::info("Sent Weekly Financial Insights to {$admin->email} for Company {$company->id}");
        }
    }
}
