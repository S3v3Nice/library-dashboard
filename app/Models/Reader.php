<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Reader
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic
 * @property \Illuminate\Support\Carbon $library_card_issue_date
 * @property \Illuminate\Support\Carbon|null $library_card_expiry_date
 * @method static \Illuminate\Database\Eloquent\Builder|Reader newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reader newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reader query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereLibraryCardExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereLibraryCardIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader wherePatronymic($value)
 * @mixin \Eloquent
 */
class Reader extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'library_card_issue_date',
        'library_card_expiry_date'
    ];

    protected $dates = [
        'library_card_issue_date',
        'library_card_expiry_date',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'library_card_issue_date' => 'date:Y-m-d',
        'library_card_expiry_date' => 'date:Y-m-d',
    ];
}
