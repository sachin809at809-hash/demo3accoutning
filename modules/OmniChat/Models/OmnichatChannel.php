<?php

namespace Modules\OmniChat\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class OmnichatChannel extends Model
{
    use Tenants;

    protected $table = 'omnichat_channels';

    protected $fillable = [
        'company_id',
        'name',
        'platform',
        'identifier',
        'credentials',
        'is_active',
        'is_ai_enabled',
    ];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
    ];

    public function conversations()
    {
        return $this->hasMany(OmnichatConversation::class, 'channel_id');
    }
}
