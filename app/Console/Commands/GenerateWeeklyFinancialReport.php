<?php

namespace App\Console\Commands;

use App\Models\Common\Company;
use App\Models\Document\Document;
use App\Models\Banking\Transaction;
use App\Services\AIService;
use App\Mail\WeeklyFinancialInsights;
use App\Utilities\Date;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GenerateWeeklyFinancialReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:weekly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile weekly metrics and email AI-generated financial insights to administrator';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Disable model cache
        config(['laravel-model-caching.enabled' => false]);

        $today = Date::today();
        $start_date = $today->copy()->subWeek()->startOfDay()->toDateTimeString();
        $end_date = $today->copy()->endOfDay()->toDateTimeString();

        $companies = Company::enabled()->cursor();

        foreach ($companies as $company) {
            $this->info("Compiling weekly report for company: {$company->name}");

            // Set current tenant company context
            $company->makeCurrent();
            $companyId = $company->id;

            try {
                // 1. Weekly Invoiced Sales
                $weeklyInvoiced = Document::invoice()
                    ->where('company_id', $companyId)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('amount');

                $weeklyInvoicedPaid = Document::invoice()
                    ->where('company_id', $companyId)
                    ->where('status', 'paid')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('amount');

                // 2. Weekly Bills
                $weeklyBilled = Document::bill()
                    ->where('company_id', $companyId)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('amount');

                $weeklyBilledPaid = Document::bill()
                    ->where('company_id', $companyId)
                    ->where('status', 'paid')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('amount');

                // 3. Weekly cash bank transactions
                $weeklyBankIncome = Transaction::income()
                    ->where('company_id', $companyId)
                    ->whereBetween('paid_at', [$start_date, $end_date])
                    ->sum('amount');

                $weeklyBankExpense = Transaction::expense()
                    ->where('company_id', $companyId)
                    ->whereBetween('paid_at', [$start_date, $end_date])
                    ->sum('amount');

                // 4. Totals receivables / payables (Outstanding)
                $totalUnpaidInvoices = Document::invoice()
                    ->where('company_id', $companyId)
                    ->where('status', '<>', 'paid')
                    ->sum('amount');

                $totalUnpaidBills = Document::bill()
                    ->where('company_id', $companyId)
                    ->where('status', '<>', 'paid')
                    ->sum('amount');

                // Compile Context Prompt
                $contextPrompt = "Please compile a weekly financial insights report for our company. Here are our bookkeeping metrics for the last 7 days (from {$start_date} to {$end_date}):
- New Invoiced Revenue (Sales Invoices created): " . money($weeklyInvoiced)->format() . " (Paid: " . money($weeklyInvoicedPaid)->format() . ")
- New Bills (Purchases/Expenses created): " . money($weeklyBilled)->format() . " (Paid: " . money($weeklyBilledPaid)->format() . ")
- Total Bank Cash Income Transactions: " . money($weeklyBankIncome)->format() . "
- Total Bank Cash Expense Transactions: " . money($weeklyBankExpense)->format() . "
- Weekly Net Cash Flow (Income - Expense): " . money($weeklyBankIncome - $weeklyBankExpense)->format() . "
- Outstanding Accounts Receivable (Unpaid Invoices): " . money($totalUnpaidInvoices)->format() . "
- Outstanding Accounts Payable (Unpaid Bills): " . money($totalUnpaidBills)->format() . "

Write a short, engaging, and professional narrative report (about 3-4 paragraphs) analyzing these weekly metrics. Highlight positive trends, warning areas (e.g., high bills or low cash flow), and specific actionable advice for the next week.";

                $aiService = new AIService();
                $insights = $aiService->generateFinancialInsights($contextPrompt);

                // Fetch administrators emails
                $users = $company->users()->where('enabled', 1)->get();
                $emails = [];
                foreach ($users as $user) {
                    if ($user->email) {
                        $emails[] = $user->email;
                    }
                }

                if (empty($emails) && setting('company.email')) {
                    $emails[] = setting('company.email');
                }

                if (empty($emails)) {
                    $this->warn("No administrators or company email found to send the report for {$company->name}.");
                    continue;
                }

                $metrics = [
                    'weeklyInvoiced' => $weeklyInvoiced,
                    'weeklyBilled' => $weeklyBilled,
                    'weeklyNetCashFlow' => $weeklyBankIncome - $weeklyBankExpense,
                    'totalUnpaidInvoices' => $totalUnpaidInvoices,
                    'totalUnpaidBills' => $totalUnpaidBills,
                ];

                foreach ($emails as $email) {
                    try {
                        Mail::to($email)->send(new WeeklyFinancialInsights($company, $insights, $metrics));
                        $this->info("Emailed weekly report to {$email} for company {$company->name}");
                    } catch (\Exception $mailEx) {
                        $this->error("Failed to mail report to {$email}: " . $mailEx->getMessage());
                        Log::error("Weekly report email fail: " . $mailEx->getMessage());
                    }
                }

            } catch (\Exception $e) {
                $this->error("Error compiling weekly report for {$company->name}: " . $e->getMessage());
                Log::error("GenerateWeeklyFinancialReport Command: " . $e->getMessage());
            }
        }

        Company::forgetCurrent();
        $this->info("Weekly financial report run finished.");
    }
}
