<?php

namespace App\Models;

use App\Traits\Owners;
use App\Traits\Sources;
use App\Traits\Tenants;
use Illuminate\Database\Eloquent\Model;

class AIAssistantUpload extends Model
{
    use Owners, Sources, Tenants;

    protected $table = 'ai_assistant_uploads';

    protected $fillable = [
        'company_id',
        'file_path',
        'status',
        'document_type',
        'extracted_data',
        'document_id',
        'transaction_id',
        'error_message',
    ];

    protected $casts = [
        'extracted_data' => 'array',
    ];

    /**
     * Get the associated document (Invoice or Bill).
     */
    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document', 'document_id');
    }

    /**
     * Get the associated transaction.
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'transaction_id');
    }
}
