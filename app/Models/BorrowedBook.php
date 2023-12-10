<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\BorrowedBook
 *
 * @property int $id
 * @property int $book_id
 * @property int $reader_id
 * @property \Illuminate\Support\Carbon $borrow_date
 * @property \Illuminate\Support\Carbon $return_date
 * @property \Illuminate\Support\Carbon|null $actual_return_date
 * @property-read \App\Models\Book $book
 * @property-read \App\Models\Reader $reader
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook query()
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook whereActualReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook whereBorrowDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook whereReaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BorrowedBook whereReturnDate($value)
 * @mixin \Eloquent
 */
class BorrowedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_date',
        'return_date',
        'actual_return_date',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function reader(): BelongsTo
    {
        return $this->belongsTo(Reader::class);
    }
}
