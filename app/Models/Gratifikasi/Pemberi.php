<?php

namespace App\Models\Gratifikasi;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Kedeka\Support\Database\Concerns\HasUlid;

class Pemberi extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'gratifikasi_pemberi';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama',
        'pekerjaan',
        'jabatan',
        'whatsapp',
        'email',
        'hubungan',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('nama', 'LIKE', $terms);
            $subquery->orWhere('pekerjaan', 'LIKE', $terms);
            $subquery->orWhere('jabatan', 'LIKE', $terms);
            $subquery->orWhere('email', 'LIKE', $terms);
            $subquery->orWhere('whatsapp', 'LIKE', $terms);
            $subquery->orWhere('hubungan', 'LIKE', $terms);
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

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
