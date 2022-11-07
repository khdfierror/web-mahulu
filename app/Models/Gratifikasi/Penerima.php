<?php

namespace App\Models\Gratifikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kedeka\Support\Database\Concerns\HasUlid;

class Penerima extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'gratifikasi_penerima';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'gratifikasi_jenis_id',
        'gratifikasi_peristiwa_id',
        'gratifikasi_pelapor_id',
        'gratifikasi_pemberi_id',
        'gratifikasi_kronologi_id',
        'uraian',
        'nominal',
        'tanggal',
        'tempat',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('uraian', 'LIKE', $terms);
            $subquery->orWhere('nominal', 'LIKE', $terms);
            $subquery->orWhere('tanggal', 'LIKE', $terms);
            $subquery->orWhere('tempat', 'LIKE', $terms);
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

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(Jenis::class, 'gratifikasi_jenis_id');
    }

    public function peristiwa(): BelongsTo
    {
        return $this->belongsTo(Peristiwa::class, 'gratifikasi_peristiwa_id');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Pelapor::class, 'gratifikasi_pelapor_id');
    }

    public function pemberi(): BelongsTo
    {
        return $this->belongsTo(Pemberi::class, 'gratifikasi_pemberi_id');
    }

    public function kronologi(): BelongsTo
    {
        return $this->belongsTo(Kronologi::class, 'gratifikasi_kronologi_id');
    }
}
