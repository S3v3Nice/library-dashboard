<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Reader
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic
 * @property int $card_id
 * @property-read \App\Models\LibraryCard|null $card
 * @method static \Illuminate\Database\Eloquent\Builder|Reader newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reader newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reader query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reader whereLastName($value)
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
    ];

    public function card(): HasOne
    {
        return $this->hasOne(LibraryCard::class);
    }
}
