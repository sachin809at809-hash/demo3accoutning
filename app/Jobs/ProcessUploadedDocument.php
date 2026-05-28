<?php

namespace App\Jobs;

use App\Abstracts\Job;
use App\Models\AIAssistantUpload;
use App\Services\AIService;
use App\Services\BookkeepingEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessUploadedDocument extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected AIAssistantUpload $upload;

    /**
     * Create a new job instance.
     *
     * @param AIAssistantUpload $upload
     */
    public function __construct(AIAssistantUpload $upload)
    {
        $this->upload = $upload;

        parent::__construct([]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info("ProcessUploadedDocument Job started for Upload ID: {$this->upload->id}");

        // 1. Update status to processing
        $this->upload->update([
            'status' => 'processing',
            'error_message' => null
        ]);

        try {
            // 2. Call AIService to analyze the document
            $aiService = new AIService();
            $extractedData = $aiService->analyzeDocument($this->upload->file_path);

            if (empty($extractedData)) {
                throw new \Exception("The AI assistant returned an empty or invalid JSON analysis.");
            }

            // 3. Save extracted JSON
            $this->upload->update([
                'extracted_data' => $extractedData
            ]);

            Log::info("Successfully extracted JSON data via AI for Upload ID {$this->upload->id}. Starting bookkeeping...");

            // 4. Run Bookkeeping Engine to generate invoices/bills
            $bookkeeper = new BookkeepingEngine();
            $bookkeeper->processExtractedData($this->upload);

            Log::info("ProcessUploadedDocument completed successfully for Upload ID: {$this->upload->id}");

        } catch (\Throwable $e) {
            Log::error("ProcessUploadedDocument failed for Upload ID: {$this->upload->id}. Error: " . $e->getMessage());

            $this->upload->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
