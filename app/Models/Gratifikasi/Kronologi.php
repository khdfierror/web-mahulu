<?php

namespace App\Models\Gratifikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Kedeka\Support\Database\Concerns\HasUlid;

class Kronologi extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'gratifikasi_kronologi';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'alasan',
        'kronologi',
        'catatan',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('alasan', 'LIKE', $terms);
            $subquery->orWhere('kronologi', 'LIKE', $terms);
            $subquery->orWhere('catatan', 'LIKE', $terms);
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

    public function uploadImage(UploadedFile $file, $orderColumn = 0)
    {
        $disk = 'upcloud';
        $path = $file->store('gratifikasi/'.$this->ulid, $disk);

        $media = \Kedeka\InertiaMedia\Models\File::create([
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'disk' => $disk,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'url' => Storage::disk($disk)->url($path),
        ]);

        $media->attachedTo($this, 'image', $orderColumn);

        return $this;
    }

    public function images()
    {
        return $this->morphMany(\Kedeka\InertiaMedia\Models\Attachment::class, 'attachable')->whereTag('image')->orderBy('order_column');
    }
}
