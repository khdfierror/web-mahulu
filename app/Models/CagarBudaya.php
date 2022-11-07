<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Kedeka\Support\Database\Concerns\HasUlid;

class CagarBudaya extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'cagar_budaya';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'cp_name',
        'cp_phone',
        'cp_email',
        'no_inventaris',
        'periode',
        'bahan',
        'ukuran',
        'keterangan',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('name', 'LIKE', $terms);
            $subquery->orWhere('cp_name', 'LIKE', $terms);
            $subquery->orWhere('cp_phone', 'LIKE', $terms);
            $subquery->orWhere('cp_email', 'LIKE', $terms);
            $subquery->orWhere('no_inventaris', 'LIKE', $terms);
            $subquery->orWhere('periode', 'LIKE', $terms);
            $subquery->orWhere('bahan', 'LIKE', $terms);
            $subquery->orWhere('ukuran', 'LIKE', $terms);
            $subquery->orWhere('keterangan', 'LIKE', $terms);
        }))->when($filters['sort'] ?? null, function ($query, $sortBy) {
            if (count($params = explode(':', $sortBy)) === 2) {
                [$column, $direction] = $params;
            } else {
                [$column] = $params;
                $direction = 'asc';
            }

            $query->orderBy($column, $direction);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
