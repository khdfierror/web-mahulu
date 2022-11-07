<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Kedeka\InertiaMedia\Models\Attachment;
use Kedeka\InertiaMedia\Models\File;
use Kedeka\Support\Database\Concerns\HasUlid;

class Applicant extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'permit_applicants';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'job',
        'agency',
        'phone',
        'email',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('name', 'LIKE', $terms);
            $subquery->orWhere('job', 'LIKE', $terms);
            $subquery->orWhere('agency', 'LIKE', $terms);
            $subquery->orWhere('phone', 'LIKE', $terms);
            $subquery->orWhere('email', 'LIKE', $terms);
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
            $path = $file->store('media', $disk);

            $media = File::create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'disk' => $disk,
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'url' => Storage::disk($disk)->url($path),
            ]);

            $media->attachedTo($this, 'image');

            if ($previous) {
                $previous->delete();
                // Storage::disk($disk)->delete($previous?->file?->path);
            }
        });

        return $this;
    }

    public function image()
    {
        return $this->morphOne(Attachment::class, 'attachable')->whereTag('image');
    }

    public function getImageUrlAttribute()
    {
        return $this->image?->file?->url;
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function permit(): BelongsTo
    {
        return $this->belongsTo(Permit::class);
    }
}
