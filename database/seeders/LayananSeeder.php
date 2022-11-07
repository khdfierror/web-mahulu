<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Kedeka\InertiaBlog\Models\Author;
use Kedeka\InertiaBlog\Models\Category;
use Kedeka\InertiaBlog\Models\Post;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // $this->categories();
            $this->layanan();
            $this->warta();
        });
    }

    public function categories()
    {
        $kategori_layanan = DB::table('piguroco_bpcb.kategori_layanans')->get()->each(fn ($item) => Category::firstOrCreate([
            'name' => $item->nama_kategori,
            'slug' => $item->slug_kategori,
        ])->update(['is_visible' => 1]));
    }

    public function layanan()
    {
        $kategori_layanan = DB::table('piguroco_bpcb.kategori_layanans')->get()->each(function ($kategori) {
            $author = Author::first();
            $ref = Category::where([
                'slug' => 'jendela',
            ])->first();

            $layanan = DB::table('piguroco_bpcb.layanans')->where('kategori_id', $kategori->id)->get()->each(function ($layanan) use ($ref, $author) {
                $path = Storage::path('data/layanan/'.$layanan->thumbnail);
                $mime = Storage::mimeType('data/layanan/'.$layanan->thumbnail);
                $size = Storage::size('data/layanan/'.$layanan->thumbnail);
                $post = Post::firstOrCreate([
                    'title' => $layanan->nama_layanan,
                    'slug' => $layanan->slug_layanan,
                ]);
                $post->update([
                    'content' => $layanan->describ,
                    'published_at' => $layanan->created_at,
                    'blog_category_id' => $ref->id,
                    'blog_author_id' => $author->id,
                ]);
                $file = new UploadedFile(
                    $path,
                    $layanan->thumbnail,
                    $mime,
                    $size,
                    false,
                    true
                );
                $post->updateImage($file);
            });
        });

        // $layanan = DB::table('piguroco_bpcb.layanans')->get();
        // dd($kategori_layanan, $layanan);
    }

    public function warta()
    {
        $author = Author::first();
        $ref = Category::where([
            'slug' => 'warta',
        ])->first();

        $warta = DB::table('piguroco_bpcb.warta_bpcbs')->get()->each(function ($warta) use ($ref, $author) {
            $path = Storage::path('data/warta/'.$warta->thumbnail);
            $mime = Storage::mimeType('data/warta/'.$warta->thumbnail);
            $size = Storage::size('data/warta/'.$warta->thumbnail);
            $post = Post::firstOrCreate([
                'title' => $warta->nama_warta,
                'slug' => $warta->slug_warta,
            ]);
            $post->update([
                'content' => $warta->describ,
                'published_at' => $warta->created_at,
                'blog_category_id' => $ref->id,
                'blog_author_id' => $author->id,
            ]);
            $file = new UploadedFile(
                $path,
                $warta->thumbnail,
                $mime,
                $size,
                false,
                true
            );
            $post->updateImage($file);
        });
    }
}
