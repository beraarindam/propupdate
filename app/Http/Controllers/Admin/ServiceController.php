<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('backend.services.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = Service::query()->count();

        $query = Service::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('summary', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('icon_class', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumns = ['sort_order', 'name', 'summary', 'icon_class', 'is_published', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', $orderDir);

        $services = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $services->map(function (Service $service) use ($token) {
            $status = $service->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $iconCell = $service->icon_class
                ? '<span class="text-muted" title="'.e($service->icon_class).'"><i class="'.e($service->icon_class).'"></i></span>'
                : '<span class="text-muted small">—</span>';

            $editUrl = route('admin.services.edit', $service);
            $deleteUrl = route('admin.services.destroy', $service);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this service?\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $service->sort_order,
                '<span class="fw-semibold">'.e(Str::limit($service->name, 80)).'</span>',
                e(Str::limit(strip_tags($service->summary), 90)),
                $iconCell,
                $status,
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

    public function create(): View
    {
        return view('backend.services.create', [
            'service' => new Service(['is_published' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'summary' => 'required|string|max:5000',
            'description' => 'nullable|string|max:20000',
            'icon_class' => 'nullable|string|max:120',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        Service::create([
            'name' => $data['name'],
            'summary' => $data['summary'],
            'description' => $data['description'] ?? null,
            'icon_class' => $data['icon_class'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service created.');
    }

    public function edit(Service $service): View
    {
        return view('backend.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'summary' => 'required|string|max:5000',
            'description' => 'nullable|string|max:20000',
            'icon_class' => 'nullable|string|max:120',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        $service->fill([
            'name' => $data['name'],
            'summary' => $data['summary'],
            'description' => $data['description'] ?? null,
            'icon_class' => $data['icon_class'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);
        $service->save();

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('status', 'Service saved.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service deleted.');
    }
}
