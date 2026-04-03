<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('frontend.blog.index', compact('posts'));
    }

    public function show(BlogPost $blog): View
    {
        if (! $blog->is_published || $blog->published_at === null) {
            abort(404);
        }

        return view('frontend.blog.show', ['post' => $blog]);
    }
}
