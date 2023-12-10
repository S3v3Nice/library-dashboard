<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\LibraryCard
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $issue_date
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property-read \App\Models\Reader $reader
 * @method static \Illuminate\Database\Eloquent\Builder|LibraryCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LibraryCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LibraryCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|LibraryCard whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LibraryCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LibraryCard whereIssueDate($value)
 * @mixin \Eloquent
 */
class LibraryCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_date',
        'expiry_date',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function reader(): BelongsTo
    {
        return $this->belongsTo(Reader::class);
    }
}
