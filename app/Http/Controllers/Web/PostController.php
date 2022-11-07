<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Kedeka\InertiaBlog\Models\Author;
use Kedeka\InertiaBlog\Models\Category;
use Kedeka\InertiaBlog\Models\Post;
use Kedeka\InertiaBlog\Models\Tag;

class PostController extends Controller
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
            ->whereNotNull('published_at')
            ->where('blog_category_id', Category::where('slug', $category)->first()?->id)
            ->get()
            ->transform(fn ($post) => [
                'id' => $post->ulid,
                'title' => $post->title,
                'slug' => $post->slug,
                // 'content' => $post->content,
                'content' => Str::words(str_replace('https://', 'https:', $post->content), 50, ' ...'),
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

    public function show(Post $post, Request $request)
    {
        $authors = Author::get()->transform(fn ($author) => [
            'id' => $author->ulid,
            'label' => $author->name,
        ]);

        $categories = Category::get()->transform(fn ($category) => [
            'id' => $category->ulid,
            'label' => $category->name,
        ]);

        $tags = Tag::orderBy('name')->pluck('name');

        return Inertia::render('Web/Post', [
            'post' => [
                'title' => $post?->title,
                'slug' => $post?->slug,
                'content' => $post?->content,
                'author' => $post->author?->name,
                'category' => $post?->category?->name,
                'tags' => $post?->tags?->pluck('name') ?: [],
                'published_at' => $post?->published_at?->format('Y-m-d'),
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
