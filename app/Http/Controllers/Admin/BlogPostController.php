<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function index(): View
    {
        return view('backend.blogs.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = BlogPost::query()->count();

        $query = BlogPost::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('excerpt', 'like', '%'.$search.'%')
                    ->orWhere('body', 'like', '%'.$search.'%')
                    ->orWhere('meta_title', 'like', '%'.$search.'%')
                    ->orWhere('meta_description', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $orderColumns = ['title', 'slug', 'is_published', 'updated_at', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'updated_at';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'updated_at';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', 'desc');

        $posts = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $posts->map(function (BlogPost $post) use ($token) {
            $status = $post->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $updated = $post->updated_at?->format('M j, Y H:i') ?? '—';

            $publicUrl = $post->is_published ? route('blog.show', $post) : null;
            $slugCell = $publicUrl
                ? '<code class="small"><a href="'.e($publicUrl).'" target="_blank" rel="noopener noreferrer">'.e($post->slug).'</a></code>'
                : '<code class="small">'.e($post->slug).'</code>';

            $editUrl = route('admin.blogs.edit', $post);
            $deleteUrl = route('admin.blogs.destroy', $post);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this post?\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                '<span class="fw-semibold">'.e(Str::limit($post->title, 70)).'</span>',
                $slugCell,
                $status,
                '<span class="text-muted small text-nowrap">'.e($updated).'</span>',
                $actions,
            ];
        })->values()->all();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function tinymceUpload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $path = $request->file('file')->store('blog/editor', 'public');
        $url = SiteSetting::resolvePublicUrl($path);

        if ($url === null) {
            Storage::disk('public')->delete($path);

            return response()->json(['error' => 'Storage path could not be resolved.'], 422);
        }

        return response()->json(['location' => $url]);
    }

    public function create(): View
    {
        return view('backend.blogs.create', [
            'post' => new BlogPost(['is_published' => false]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:5000',
            'body' => 'required|string|max:500000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image_url' => 'nullable|string|max:2000',
            'featured_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'is_published' => 'nullable|boolean',
        ]);

        $slugBase = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($data['title']);
        $slug = $this->uniqueSlug($slugBase);

        $publishedAt = null;
        if ($request->boolean('is_published')) {
            $publishedAt = now();
        }

        $path = null;
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blog', 'public');
        }

        $post = BlogPost::create([
            'slug' => $slug,
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'featured_image_url' => $data['featured_image_url'] ?? null,
            'featured_image_path' => $path,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.blogs.edit', $post)
            ->with('status', 'Post created.');
    }

    public function edit(BlogPost $blog): View
    {
        return view('backend.blogs.edit', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('blog_posts', 'slug')->ignore($blog->id),
            ],
            'excerpt' => 'nullable|string|max:5000',
            'body' => 'required|string|max:500000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image_url' => 'nullable|string|max:2000',
            'featured_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_featured_image' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $slugInput = trim((string) $request->input('slug', ''));
        if ($slugInput !== '') {
            $slug = $this->uniqueSlug(Str::slug($slugInput), $blog->id);
        } else {
            $slug = $this->uniqueSlug(Str::slug($data['title']), $blog->id);
        }

        $publishedAt = $blog->published_at;
        if ($request->boolean('is_published')) {
            if ($publishedAt === null) {
                $publishedAt = now();
            }
        } else {
            $publishedAt = null;
        }

        $blog->fill([
            'slug' => $slug,
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'featured_image_url' => $data['featured_image_url'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $publishedAt,
        ]);

        if ($request->boolean('remove_featured_image')) {
            if ($blog->featured_image_path) {
                Storage::disk('public')->delete($blog->featured_image_path);
            }
            $blog->featured_image_path = null;
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image_path) {
                Storage::disk('public')->delete($blog->featured_image_path);
            }
            $blog->featured_image_path = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->save();

        return redirect()
            ->route('admin.blogs.edit', $blog)
            ->with('status', 'Post saved.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        if ($blog->featured_image_path) {
            Storage::disk('public')->delete($blog->featured_image_path);
        }
        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('status', 'Post deleted.');
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'post';
        $candidate = $slug;
        $i = 2;
        while (BlogPost::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }
}
