<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Kedeka\InertiaBlog\Models\Author;
use Kedeka\InertiaBlog\Models\Category;
use Kedeka\InertiaBlog\Models\Post;
use Kedeka\InertiaBlog\Models\Tag;

class JendelaController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
            'author',
            'category',
            'tags',
        ]));

        $authors = Author::get()->transform(fn ($author) => [
            'id' => $author->ulid,
            'label' => $author->name,
        ]);

        $categories = Category::get()->transform(fn ($category) => [
            'id' => $category->ulid,
            'label' => $category->name,
        ]);
        $category = $request->get('category') ?: 'warta';

        $tags = Tag::orderBy('name')->pluck('name');

        $posts = Post::filter($filters)
            ->latest('published_at')
            ->where('blog_category_id', Category::where('slug', $category)->first()?->id)
            ->get()
            ->transform(fn ($post) => [
                'id' => $post->ulid,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'category' => [
                    'name' => $post->category?->name,
                    'slug' => $post->category?->slug,
                ],
                'image' => $post->image_url,
                'published_at' => $post->published_at,
            ]);

        return Inertia::render('Web/PostGrid', [
            'filters' => $filters,
            'params' => [
                'authors' => $authors,
                'categories' => $categories,
                'tags' => $tags,
                'category' => ucfirst($category),
            ],
            'posts' => $posts,
        ]);
    }

    public function show($slug, Request $request)
    {
        $post = Post::where('slug', $slug)->first();

        return Inertia::render('Web/Jendela', [
            'post' => [
                'title' => $post?->title,
                'slug' => $post?->slug,
                'content' => $post?->content,
                'author' => $post->author?->name,
                'category' => $post?->category?->name,
                'tags' => $post?->tags?->pluck('name') ?: [],
                'published_at' => $post?->published_at->format('Y-m-d'),
                'image' => $post?->image_url,
            ],
            'params' => [
                // 'authors' => $authors,
                // 'categories' => $categories,
                // 'tags' => $tags,
            ],
        ]);
    }
}
