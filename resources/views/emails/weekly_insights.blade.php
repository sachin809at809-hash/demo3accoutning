<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); padding: 30px 20px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; opacity: 0.9; font-size: 14px; }
        .content { padding: 30px; }
        .metrics-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px; }
        .metric-card { background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; }
        .metric-title { font-size: 12px; text-transform: uppercase; color: #6b7280; font-weight: bold; margin-bottom: 5px; }
        .metric-value { font-size: 20px; font-weight: bold; color: #111827; }
        .net-positive { color: #10b981; }
        .net-negative { color: #ef4444; }
        .analysis-box { background: #f5f3ff; border-left: 4px solid #8b5cf6; padding: 20px; border-radius: 4px; }
        .analysis-box h3 { margin-top: 0; color: #6d28d9; font-size: 16px; margin-bottom: 10px; }
        .analysis-box p { line-height: 1.6; color: #4b5563; font-size: 14px; margin-bottom: 15px; }
        .analysis-box p:last-child { margin-bottom: 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Weekly Financial Insights</h1>
            <p>{{ $data['company_name'] }}</p>
        </div>
        
        <div class="content">
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-title">Cash In (Income)</div>
                    <div class="metric-value">{{ $data['income'] }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-title">Cash Out (Expenses)</div>
                    <div class="metric-value">{{ $data['expenses'] }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-title">Net Cash Flow</div>
                    <div class="metric-value {{ strpos($data['net'], '-') !== false ? 'net-negative' : 'net-positive' }}">{{ $data['net'] }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-title">New Invoiced Sales</div>
                    <div class="metric-value text-indigo-600">{{ $data['newInvoices'] }}</div>
                </div>
            </div>

            <div class="analysis-box">
                <h3>Executive Summary (AI Generated)</h3>
                @foreach($data['analysis'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>

        <div class="footer">
            Generated automatically by your Apex ERP AI Assistant.<br>
            Powered by Gemini/Groq &middot; Do not reply to this email.
        </div>
    </div>
</body>
</html>
