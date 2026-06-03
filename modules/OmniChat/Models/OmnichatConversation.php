<?php

namespace Modules\OmniChat\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common\Contact;
use App\Traits\Tenants;

class OmnichatConversation extends Model
{
    use Tenants;

    protected $table = 'omnichat_conversations';

    protected $fillable = [
        'company_id',
        'channel_id',
        'contact_id',
        'external_id',
        'name',
        'avatar',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function channel()
    {
        return $this->belongsTo(OmnichatChannel::class, 'channel_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function messages()
    {
        return $this->hasMany(OmnichatMessage::class, 'conversation_id');
    }
}
