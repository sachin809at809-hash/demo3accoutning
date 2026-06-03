<?php

namespace Modules\OmniChat\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class OmnichatMessage extends Model
{
    use Tenants;

    protected $table = 'omnichat_messages';

    protected $fillable = [
        'company_id',
        'conversation_id',
        'external_id',
        'direction',
        'body',
        'type',
        'attachments',
        'status',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(OmnichatConversation::class, 'conversation_id');
    }
}
