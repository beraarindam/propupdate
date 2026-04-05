<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Page;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.projects.index', [
            'page' => Page::bySlug('projects'),
            'projects' => $projects,
        ]);
    }

    public function show(Project $project): View
    {
        if (! $project->is_published || $project->published_at === null) {
            throw new NotFoundHttpException;
        }

        return view('frontend.projects.show', [
            'page' => null,
            'project' => $project,
        ]);
    }

    public function submitEnquiry(Request $request, Project $project): RedirectResponse
    {
        if (! $project->is_published || $project->published_at === null) {
            throw new NotFoundHttpException;
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:32',
            'message' => 'required|string|max:4000',
        ]);

        Enquiry::create([
            'source' => Enquiry::SOURCE_PROJECT,
            'project_id' => $project->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => Str::limit('Project: '.$project->title, 200),
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->withFragment('pu-project-request')
            ->with('project_enquiry_status', 'Thanks — your request was sent. We will get back to you shortly.');
    }
}
