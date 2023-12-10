<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\JobPosition
 *
 * @property int $id
 * @property string $name
 * @property float $salary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereSalary($value)
 * @mixin \Eloquent
 */
class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'salary',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
