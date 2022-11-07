<?php

namespace App\Models;

use App\Enums\AreaGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'code',
        'name',
        'abbreviation',
    ];

    protected $casts = [
        'group' => AreaGroup::class,
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('name', 'LIKE', $terms);
            $subquery->orWhere('code', 'LIKE', $terms);
            $subquery->orWhere('group', 'LIKE', $terms);
        }))->when($filters['group'] ?? null, fn ($query, $group) => $query->where(function ($subquery) use ($group) {
            $subquery->where('group', $group);
        }))->when($filters['sort'] ?? null, function ($query, $sortBy) {
            if (count($params = explode(':', $sortBy)) === 2) {
                [$column, $direction] = $params;
            } else {
                [$column] = $params;
                $direction = 'asc';
            }

            $query->orderBy($column, $direction);
        });
    }

    public function scopeProvince($query)
    {
        return $query->where('group', AreaGroup::Provinsi);
    }

    public function scopeDistrict($query)
    {
        return $query->where('group', AreaGroup::Kecamatan);
    }

    public function scopeWard($query)
    {
        return $query->where('group', AreaGroup::Kelurahan);
    }

    public function scopeKabkot($query)
    {
        return $query->whereIn('group', [AreaGroup::Kabupaten, AreaGroup::Kota]);
    }
}
