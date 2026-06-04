<?php

namespace Modules\OmniChat\Models;

use Illuminate\Database\Eloquent\Model;

class OmnichatCannedResponse extends Model
{
    protected $table = 'omnichat_canned_responses';

    protected $fillable = [
        'company_id',
        'title',
        'shortcut',
        'body',
    ];
}
