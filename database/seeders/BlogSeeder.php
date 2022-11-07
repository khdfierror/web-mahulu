<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Kedeka\InertiaBlog\Models;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->author();
            $this->category();
            $this->warta();
            $this->jendela();
        });
    }

    public function author()
    {
        $data = [
            [
                'name' => 'BPCB',
                'email' => 'admin@bpcbkalimantan.id',
                'bio' => '',
                'instagram' => 'https://instagram.com/bpcbkaltim',
                'twitter' => 'https://twitter.com/bpcbkaltim',
            ],
        ];

        foreach ($data as $item) {
            Models\Author::firstOrCreate($item);
        }
    }

    public function category()
    {
        $data = [
            [
                'name' => 'Warta',
                'slug' => 'warta',
                'description' => '',
                'is_visible' => true,
            ],
            [
                'name' => 'Jendela',
                'slug' => 'jendela',
                'description' => '',
                'is_visible' => true,
            ],
        ];

        foreach ($data as $item) {
            Models\Category::firstOrCreate($item);
        }
    }

    public function warta()
    {
        $data = [
            [
                'title' => 'Artikel',
                'slug' => 'artikel',
                'category' => 'warta',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',
                'published_at' => now(),
            ],
            [
                'title' => 'Berita',
                'slug' => 'berita',
                'category' => 'warta',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',
                'published_at' => now(),
            ],
            [
                'title' => 'Pengumuman',
                'slug' => 'pengumuman',
                'category' => 'warta',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ut labore et dolore magna aliqua.',
                'published_at' => now(),
            ],
        ];

        $author = Models\Author::first();
        $categories = Models\Category::get();
        foreach ($data as $item) {
            $category = $categories->where('slug', $item['category'])->first();
            Models\Post::firstOrCreate(Arr::except($item, ['category', 'published_at']))->update([
                'blog_author_id' => $author->id,
                'blog_category_id' => $category->id,
                'published_at' => $item['published_at'],
            ]);
        }
    }

    public function jendela()
    {
        $data = [
            [
                'title' => 'Dokumentasi Dan Publikasi',
                'slug' => 'dokumentasi-dan-publikasi',
                'category' => 'jendela',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'published_at' => now(),
            ],
            [
                'title' => 'Pelindungan',
                'slug' => 'pelindungan',
                'category' => 'jendela',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'published_at' => now(),
            ],
        ];

        $author = Models\Author::first();
        $categories = Models\Category::get();
        foreach ($data as $item) {
            $category = $categories->where('slug', $item['category'])->first();
            Models\Post::firstOrCreate(Arr::except($item, ['category', 'published_at']))->update([
                'blog_author_id' => $author->id,
                'blog_category_id' => $category->id,
                'published_at' => $item['published_at'],
            ]);
        }
    }
}
