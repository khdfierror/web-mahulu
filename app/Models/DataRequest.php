<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Kedeka\Support\Database\Concerns\HasUlid;

class DataRequest extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'services_data_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'categories_id',
        'cagar_budaya_id',
        'permit_applicant_id',
        'concept',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('name', 'LIKE', $terms);
            $subquery->orWhere('concept', 'LIKE', $terms);
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

    public function updateImage(UploadedFile $file)
    {
        tap($this->image, function ($previous) use ($file) {
            $disk = config('kedeka.media.disk', 'public');
            $path = $file->store('file', $disk);

            $media = \Kedeka\InertiaMedia\Models\File::create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'disk' => $disk,
                // 'mime' => $file->getClientMimeType(),
                'mime' => substr($file->getClientMimeType(), 0, 50),
                'size' => $file->getSize(),
                'url' => Storage::disk($disk)->url($path),
            ]);

            $media->attachedTo($this, 'file');

            if ($previous) {
                $previous->delete();
                // Storage::disk($disk)->delete($previous?->file?->path);
            }
        });

        return $this;
    }

    public function image()
    {
        return $this->morphOne(\Kedeka\InertiaMedia\Models\Attachment::class, 'attachable')->whereTag('file');
    }

    public function getImageUrlAttribute()
    {
        return $this->image?->file?->url;
    }

    public function uploadImage(UploadedFile $file, $orderColumn = 0)
    {
        $disk = 'upcloud';
        $path = $file->store('permohonan-data/'.$this->ulid, $disk);

        $media = \Kedeka\InertiaMedia\Models\File::create([
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'disk' => $disk,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'url' => Storage::disk($disk)->url($path),
        ]);

        $media->attachedTo($this, 'file', $orderColumn);

        return $this;
    }

    public function images()
    {
        return $this->morphMany(\Kedeka\InertiaMedia\Models\Attachment::class, 'attachable')->whereTag('file')->orderBy('order_column');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PermitCategories::class, 'categories_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'permit_applicant_id');
    }

    public function cagar_budaya(): BelongsTo
    {
        return $this->belongsTo(CagarBudaya::class);
    }
}
