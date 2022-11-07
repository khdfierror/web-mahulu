<?php

namespace App\Models\Wbs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Kedeka\Support\Database\Concerns\HasUlid;

class Laporan extends Model
{
    use HasFactory, HasUlid;

    /**
     * @var string
     */
    protected $table = 'wbs_laporan';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'kategori_id',
        'pelapor_id',
        'what',
        'where',
        'when',
        'who',
        'how',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? null, fn ($query, $terms) => $query->where(function ($subquery) use ($terms) {
            $terms = '%'.$terms.'%';
            $subquery->where('what', 'LIKE', $terms);
            $subquery->where('where', 'LIKE', $terms);
            $subquery->where('when', 'LIKE', $terms);
            $subquery->where('who', 'LIKE', $terms);
            $subquery->where('how', 'LIKE', $terms);
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

    public function uploadImage(UploadedFile $file, $orderColumn = 0)
    {
        $disk = 'upcloud';
        $path = $file->store('wbs/'.$this->ulid, $disk);

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

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Pelapor::class, 'pelapor_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
