<?php

namespace App\Models;

use App\Enums\AreaGroup;
// use App\Models\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kedeka\Support\Database\Concerns\HasUlid;

class Address extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'country_code',
        'state_code',
        'province_code',
        'city_code',
        'district_code',
        'ward_code',
        'address',
        'postal_code',
        'name',
        'phone',
        'is_default',
        'lat',
        'lng',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'province_code', 'code')->where('group', AreaGroup::Provinsi);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'city_code', 'code')->whereIn('group', [AreaGroup::Kabupaten, AreaGroup::Kota]);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'district_code', 'code')->where('group', AreaGroup::Kecamatan);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'ward_code', 'code')->where('group', AreaGroup::Kelurahan);
    }
}
