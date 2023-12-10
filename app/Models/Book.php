<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Book
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property int $year
 * @property int $count
 * @property int $author_id
 * @property int $genre_id
 * @property-read \App\Models\BookAuthor $author
 * @property-read \App\Models\BookGenre $genre
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereYear($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'count'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(BookAuthor::class, 'author_id');
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(BookGenre::class, 'genre_id');
    }
}
