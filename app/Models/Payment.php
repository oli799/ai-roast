<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Payment
 *
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment query()
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $stripe_id
 * @property string $url
 * @property string|null $computer_image_url
 * @property string|null $phone_image_url
 * @property string|null $roast
 * @property Carbon|null $email_sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereEmail($value)
 * @method static Builder|Payment whereEmailSentAt($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereName($value)
 * @method static Builder|Payment whereRoast($value)
 * @method static Builder|Payment whereStripeId($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @method static Builder|Payment whereUrl($value)
 * @method static Builder|Payment whereComputerImageUrl($value)
 * @method static Builder|Payment wherePhoneImageUrl($value)
 * @method static Builder|Payment whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasUuid;

    protected $casts = [
        'parseable' => 'boolean',
        'paid_at' => 'datetime',
        'parse_started' => 'datetime',
        'parsed_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'roast' => 'array',
    ];
}
