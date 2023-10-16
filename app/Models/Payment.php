<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Payment
 *
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment query()
 *
 * @mixin \Eloquent
 */
class Payment extends Model
{
    protected $casts = [
        'email_sent_at' => 'datetime',
    ];
}
