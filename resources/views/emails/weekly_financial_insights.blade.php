@php
    // Convert Markdown to HTML with a robust fallback
    $htmlInsights = $insights;
    if (class_exists(\Illuminate\Support\Str::class) && method_exists(\Illuminate\Support\Str::class, 'markdown')) {
        $htmlInsights = \Illuminate\Support\Str::markdown($insights);
    } else {
        $htmlInsights = e($insights);
        $htmlInsights = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $htmlInsights);
        $htmlInsights = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $htmlInsights);
        $htmlInsights = preg_replace('/^\s*-\s+(.*?)$/m', '<li>$1</li>', $htmlInsights);
        $htmlInsights = nl2br($htmlInsights);
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Financial Insights</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                
                <!-- Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 32px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">{{ config('app.name', 'Apex Accounting') }}</h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 6px 0 0 0; font-size: 14px; font-weight: 500;">Weekly Financial Insights Report</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            
                            <!-- Welcome -->
                            <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #1f2937;">Hello,</p>
                            <p style="margin: 0 0 32px 0; font-size: 14px; line-height: 1.5; color: #4b5563;">
                                Here is the weekly financial digest for <strong>{{ $company->name }}</strong>. Our autonomous AI Accountant has synthesized your bookkeeping records to provide these strategic metrics and insights.
                            </p>

                            <!-- Metrics Grid Header -->
                            <h3 style="margin: 0 0 16px 0; font-size: 14px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px;">Weekly Performance Metrics</h3>

                            <!-- Metrics Grid -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 32px;">
                                <tr>
                                    <!-- Left Column -->
                                    <td width="48%" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <!-- New Revenues -->
                                            <tr>
                                                <td style="background-color: #f9fafb; border: 1px solid #f3f4f6; border-radius: 10px; padding: 16px; margin-bottom: 12px; display: block;">
                                                    <span style="font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase;">Weekly Sales (Invoiced)</span>
                                                    <h2 style="margin: 6px 0 0 0; font-size: 20px; font-weight: 700; color: #10b981;">{{ money($metrics['weeklyInvoiced'])->format() }}</h2>
                                                </td>
                                            </tr>
                                            <!-- Unpaid Invoices -->
                                            <tr>
                                                <td style="background-color: #f9fafb; border: 1px solid #f3f4f6; border-radius: 10px; padding: 16px; display: block;">
                                                    <span style="font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase;">Total Receivables (A/R)</span>
                                                    <h2 style="margin: 6px 0 0 0; font-size: 20px; font-weight: 700; color: #4f46e5;">{{ money($metrics['totalUnpaidInvoices'])->format() }}</h2>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    
                                    <!-- Spacer -->
                                    <td width="4%"></td>

                                    <!-- Right Column -->
                                    <td width="48%" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <!-- Weekly Expenses -->
                                            <tr>
                                                <td style="background-color: #f9fafb; border: 1px solid #f3f4f6; border-radius: 10px; padding: 16px; margin-bottom: 12px; display: block;">
                                                    <span style="font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase;">Weekly Purchases (Billed)</span>
                                                    <h2 style="margin: 6px 0 0 0; font-size: 20px; font-weight: 700; color: #ef4444;">{{ money($metrics['weeklyBilled'])->format() }}</h2>
                                                </td>
                                            </tr>
                                            <!-- Unpaid Bills -->
                                            <tr>
                                                <td style="background-color: #f9fafb; border: 1px solid #f3f4f6; border-radius: 10px; padding: 16px; display: block;">
                                                    <span style="font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase;">Total Payables (A/P)</span>
                                                    <h2 style="margin: 6px 0 0 0; font-size: 20px; font-weight: 700; color: #f59e0b;">{{ money($metrics['totalUnpaidBills'])->format() }}</h2>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Cash Flow Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 40px;">
                                <tr>
                                    <td style="background: rgba(99, 102, 241, 0.05); border: 1px dashed rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 20px; text-align: center;">
                                        <span style="font-size: 12px; font-weight: 600; color: #6366f1; text-transform: uppercase; letter-spacing: 0.5px;">Weekly Net Cash Flow (Bank Accounts)</span>
                                        <h2 style="margin: 8px 0 0 0; font-size: 24px; font-weight: 800; color: {{ $metrics['weeklyNetCashFlow'] >= 0 ? '#10b981' : '#ef4444' }};">
                                            {{ $metrics['weeklyNetCashFlow'] >= 0 ? '+' : '' }}{{ money($metrics['weeklyNetCashFlow'])->format() }}
                                        </h2>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin-bottom: 32px;">

                            <!-- AI Analysis Card -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; display: block; border-left: 4px solid #8b5cf6;">
                                        <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700; color: #8b5cf6; display: flex; align-items: center;">
                                            AI Financial Analysis & Insights
                                        </h3>
                                        <div style="font-size: 14px; line-height: 1.6; color: #374151; font-weight: 400; word-break: break-word;">
                                            {!! $htmlInsights !!}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 32px 40px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #9ca3af; font-weight: 500;">
                                Sent automatically by Apex Accounting AI Assistant.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #cbd5e1;">
                                &copy; {{ date('Y') }} {{ $company->name }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                
            </td>
        </tr>
    </table>

</body>
</html>
