<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\BookAuthor
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Book> $books
 * @property-read int|null $books_count
 * @method static \Illuminate\Database\Eloquent\Builder|BookAuthor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookAuthor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookAuthor query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookAuthor whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookAuthor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookAuthor whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookAuthor wherePatronymic($value)
 * @mixin \Eloquent
 */
class BookAuthor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
    ];

    public $timestamps = false;

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
