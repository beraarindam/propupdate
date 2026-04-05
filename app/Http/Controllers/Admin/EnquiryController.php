<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(): View
    {
        return view('backend.enquiries.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = Enquiry::query()->count();

        $query = Enquiry::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('subject', 'like', '%'.$search.'%')
                    ->orWhere('message', 'like', '%'.$search.'%')
                    ->orWhere('source', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $orderColumns = ['created_at', 'source', 'name', 'email', 'read_at', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'created_at';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'created_at';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', 'desc');

        $enquiries = $query->skip($start)->take($length)->get();

        $data = $enquiries->map(function (Enquiry $enquiry): array {
            $received = $enquiry->created_at?->format('M j, Y H:i') ?? '';

            $source = '<span class="badge bg-primary bg-opacity-10 text-primary">'.e($enquiry->sourceLabel()).'</span>';

            $readCell = $enquiry->read_at
                ? '<i class="bx bx-check text-success"></i>'
                : '<i class="bx bx-envelope text-warning"></i>';

            $viewUrl = route('admin.enquiries.show', $enquiry);
            $actions = '<a href="'.e($viewUrl).'" class="btn btn-sm btn-primary">View</a>';

            return [
                '<span class="text-muted small text-nowrap">'.e($received).'</span>',
                $source,
                e($enquiry->name),
                '<span class="small"><a href="mailto:'.e($enquiry->email).'">'.e($enquiry->email).'</a></span>',
                '<div class="text-center">'.$readCell.'</div>',
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

    public function show(Enquiry $enquiry): View
    {
        $enquiry->load(['property', 'exclusiveResaleListing', 'project']);
        $enquiry->markRead();

        return view('backend.enquiries.show', compact('enquiry'));
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();

        return redirect()
            ->route('admin.enquiries.index')
            ->with('status', 'Enquiry deleted.');
    }
}
