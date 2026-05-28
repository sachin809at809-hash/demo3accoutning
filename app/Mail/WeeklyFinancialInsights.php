<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyFinancialInsights extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $insights;
    public $metrics;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $company
     * @param  string  $insights
     * @param  array  $metrics
     */
    public function __construct($company, string $insights, array $metrics)
    {
        $this->company = $company;
        $this->insights = $insights;
        $this->metrics = $metrics;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "[" . config('app.name', 'Apex Accounting') . "] Weekly Financial Insights Report - {$this->company->name}";

        return $this->subject($subject)
                    ->view('emails.weekly_financial_insights');
    }
}
