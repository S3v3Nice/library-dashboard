<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\BookGenre
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Book> $books
 * @property-read int|null $books_count
 * @method static \Illuminate\Database\Eloquent\Builder|BookGenre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookGenre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookGenre query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookGenre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookGenre whereName($value)
 * @mixin \Eloquent
 */
class BookGenre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
