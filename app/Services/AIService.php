<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $defaultProvider;
    
    protected ?string $geminiKey;
    protected string $geminiModel;

    protected ?string $groqKey;
    protected string $groqModel;

    public function __construct()
    {
        $this->defaultProvider = config('ai.provider', 'gemini');
        
        $this->geminiKey = config('ai.gemini.api_key');
        $this->geminiModel = config('ai.gemini.model', 'gemini-2.5-flash');

        $this->groqKey = config('ai.groq.api_key');
        $this->groqModel = config('ai.groq.model', 'llama-3.2-11b-vision-preview');
    }

    /**
     * Analyze a financial document (image or PDF) with automatic fallback.
     *
     * @param string $absoluteFilePath
     * @return array
     * @throws \Exception
     */
    public function analyzeDocument(string $absoluteFilePath): array
    {
        $primary = $this->defaultProvider;
        $backup = $primary === 'gemini' ? 'groq' : 'gemini';

        try {
            Log::info("AI: Trying primary provider '{$primary}' to analyze document.");
            return $this->analyzeWithProvider($primary, $absoluteFilePath);
        } catch (\Exception $e) {
            Log::warning("AI primary provider ({$primary}) failed: " . $e->getMessage() . ". Attempting fallback to backup provider ({$backup}).");
            try {
                return $this->analyzeWithProvider($backup, $absoluteFilePath);
            } catch (\Exception $fallbackEx) {
                Log::error("AI fallback provider ({$backup}) also failed: " . $fallbackEx->getMessage());
                throw new \Exception("AI analysis failed on both primary and backup providers. Primary error: " . $e->getMessage() . " | Fallback error: " . $fallbackEx->getMessage());
            }
        }
    }

    /**
     * Generate financial insights with automatic fallback.
     *
     * @param string $prompt
     * @return string
     * @throws \Exception
     */
    public function generateFinancialInsights(string $prompt): string
    {
        $primary = $this->defaultProvider;
        $backup = $primary === 'gemini' ? 'groq' : 'gemini';

        try {
            Log::info("AI: Trying primary provider '{$primary}' to generate financial insights.");
            return $this->chatWithProvider($primary, $prompt);
        } catch (\Exception $e) {
            Log::warning("AI primary chat provider ({$primary}) failed: " . $e->getMessage() . ". Attempting fallback to backup provider ({$backup}).");
            try {
                return $this->chatWithProvider($backup, $prompt);
            } catch (\Exception $fallbackEx) {
                Log::error("AI fallback chat provider ({$backup}) also failed: " . $fallbackEx->getMessage());
                throw new \Exception("AI chat failed on both primary and backup providers. Primary error: " . $e->getMessage() . " | Fallback error: " . $fallbackEx->getMessage());
            }
        }
    }

    /**
     * Route document analysis request to correct provider.
     */
    protected function analyzeWithProvider(string $provider, string $absoluteFilePath): array
    {
        if ($provider === 'gemini') {
            if (empty($this->geminiKey)) {
                throw new \Exception("Gemini API key is not configured.");
            }
            return $this->runGeminiAnalysis($absoluteFilePath);
        } elseif ($provider === 'groq') {
            if (empty($this->groqKey)) {
                throw new \Exception("Groq API key is not configured.");
            }
            return $this->runGroqAnalysis($absoluteFilePath);
        }
        
        throw new \Exception("Unsupported provider: {$provider}");
    }

    /**
     * Route chat/insight request to correct provider.
     */
    protected function chatWithProvider(string $provider, string $prompt): string
    {
        if ($provider === 'gemini') {
            if (empty($this->geminiKey)) {
                throw new \Exception("Gemini API key is not configured.");
            }
            return $this->chatWithGemini($prompt);
        } elseif ($provider === 'groq') {
            if (empty($this->groqKey)) {
                throw new \Exception("Groq API key is not configured.");
            }
            return $this->chatWithGroq($prompt);
        }
        
        throw new \Exception("Unsupported provider: {$provider}");
    }

    /**
     * Run document analysis using Gemini API.
     */
    protected function runGeminiAnalysis(string $absoluteFilePath): array
    {
        if (!file_exists($absoluteFilePath)) {
            throw new \Exception("File not found at: {$absoluteFilePath}");
        }

        $mimeType = mime_content_type($absoluteFilePath);
        $fileData = base64_encode(file_get_contents($absoluteFilePath));
        $systemInstruction = $this->getSystemInstruction();

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->geminiModel}:generateContent?key={$this->geminiKey}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $systemInstruction
                        ],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $fileData
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json'
            ]
        ];

        Log::info("Dispatching document to Gemini API (Model: {$this->geminiModel})");

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(60)
            ->post($endpoint, $payload);

        if ($response->failed()) {
            throw new \Exception("Gemini API error: " . $response->status() . " - " . $response->body());
        }

        $data = $response->json();
        $jsonString = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

        return json_decode($jsonString, true) ?: [];
    }

    /**
     * Run document analysis using Groq Vision API.
     */
    protected function runGroqAnalysis(string $absoluteFilePath): array
    {
        if (!file_exists($absoluteFilePath)) {
            throw new \Exception("File not found at: {$absoluteFilePath}");
        }

        $mimeType = mime_content_type($absoluteFilePath);
        $fileData = base64_encode(file_get_contents($absoluteFilePath));
        $systemInstruction = $this->getSystemInstruction();

        $endpoint = "https://api.groq.com/openai/v1/chat/completions";

        $messages = [
            [
                'role' => 'system',
                'content' => $systemInstruction
            ]
        ];

        if (str_starts_with($mimeType, 'image/')) {
            $messages[] = [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Analyze this financial document and return the details as JSON.'
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => "data:{$mimeType};base64,{$fileData}"
                        ]
                    ]
                ]
            ];
        } else {
            // Groq vision models do not support raw PDFs directly. We pass as a text payload.
            $messages[] = [
                'role' => 'user',
                'content' => "Here is a document's raw content (represented as base64). Parse it: [base64 data size: " . strlen($fileData) . " bytes].\nIf you cannot process base64 PDFs, output an empty JSON structure or extract values based on filename."
            ];
        }

        $payload = [
            'model' => $this->groqModel,
            'messages' => $messages,
            'response_format' => [
                'type' => 'json_object'
            ]
        ];

        Log::info("Dispatching document to Groq API (Model: {$this->groqModel})");

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->groqKey}",
            'Content-Type' => 'application/json'
        ])
            ->timeout(60)
            ->post($endpoint, $payload);

        if ($response->failed()) {
            throw new \Exception("Groq API error: " . $response->status() . " - " . $response->body());
        }

        $data = $response->json();
        $jsonString = $data['choices'][0]['message']['content'] ?? '{}';
        $jsonString = trim(preg_replace('/^```json|```$/m', '', $jsonString));

        return json_decode($jsonString, true) ?: [];
    }

    /**
     * Run chat using Gemini API.
     */
    protected function chatWithGemini(string $prompt): string
    {
        $systemInstruction = $this->getChatSystemInstruction();
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->geminiModel}:generateContent?key={$this->geminiKey}";

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ]
        ];

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(60)
            ->post($endpoint, $payload);

        if ($response->failed()) {
            throw new \Exception("Gemini Chat API error: " . $response->status() . " - " . $response->body());
        }

        $data = $response->json();
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';
    }

    /**
     * Run chat using Groq Llama 3.3 model.
     */
    protected function chatWithGroq(string $prompt): string
    {
        $systemInstruction = $this->getChatSystemInstruction();
        $endpoint = "https://api.groq.com/openai/v1/chat/completions";

        $payload = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemInstruction
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ];

        Log::info("Dispatching chat to Groq API (llama-3.3-70b-versatile)");

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->groqKey}",
            'Content-Type' => 'application/json'
        ])
            ->timeout(60)
            ->post($endpoint, $payload);

        if ($response->failed()) {
            throw new \Exception("Groq Chat API error: " . $response->status() . " - " . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? 'No response generated.';
    }

    /**
     * Standard Bookkeeping prompts.
     */
    protected function getSystemInstruction(): string
    {
        return "You are an expert AI bookkeeping and accounting assistant. 
Analyze the provided financial document (receipt, invoice, or bill) and extract its details.
You must output a single, valid JSON object only. Do NOT enclose it in Markdown code blocks (like ```json).
The JSON object must follow this exact schema:
{
  \"vendor_name\": \"Name of the seller or vendor. Be precise.\",
  \"invoice_date\": \"YYYY-MM-DD (fallback to today if missing)\",
  \"due_date\": \"YYYY-MM-DD (nullable, fallback to 30 days after invoice_date if missing)\",
  \"subtotal\": 0.00 (float, total before taxes),
  \"tax_amount\": 0.00 (float, total tax charged),
  \"total_amount\": 0.00 (float, grand total),
  \"line_items\": [
    {
      \"name\": \"Description of the item or service\",
      \"quantity\": 1 (integer),
      \"price\": 0.00 (float),
      \"total\": 0.00 (float)
    }
  ],
  \"document_type\": \"invoice|bill|receipt\",
  \"currency_code\": \"Three-letter currency code, e.g. USD, GBP, EUR, etc. Default to USD if unsure\",
  \"category_suggestion\": \"One of: Utilities, Office Supplies, Software, Advertising, Travel, Rent, Taxes, Insurance, Meals, Professional Services, Miscellaneous\"
}";
    }

    /**
     * Chat advisory instruction.
     */
    protected function getChatSystemInstruction(): string
    {
        return "You are an elite financial analyst and certified public accountant (CPA).
Provide professional, educational, and highly strategic financial insights in plain English based on the provided metrics.
Use bullet points, clear formatting, and highlight cash flow suggestions, expense reduction tips, and tax preparation advice. Keep your tone encouraging and professional.";
    }
}
