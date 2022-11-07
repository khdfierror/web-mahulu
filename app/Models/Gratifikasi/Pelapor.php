<?php

namespace App\Models\Gratifikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kedeka\Support\Database\Concerns\HasUlid;

class Pelapor extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'gratifikasi_pelapor';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama',
        'nomor_identitas',
        'whatsapp',
        'email',
        'golongan',
        'satker',
        'unit_tugas',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('nama', 'LIKE', $terms);
            $subquery->orWhere('nomor_identitas', 'LIKE', $terms);
            $subquery->orWhere('whatsapp', 'LIKE', $terms);
            $subquery->orWhere('email', 'LIKE', $terms);
            $subquery->orWhere('golongan', 'LIKE', $terms);
            $subquery->orWhere('satker', 'LIKE', $terms);
            $subquery->orWhere('unit_tugas', 'LIKE', $terms);
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

    public function penerima(): HasMany
    {
        return $this->hasMany(Penerima::class);
    }
}
