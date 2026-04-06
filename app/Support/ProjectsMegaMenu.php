<?php

namespace App\Support;

use App\Models\Project;
use Illuminate\Support\Facades\Schema;

final class ProjectsMegaMenu
{
    private const LIMIT = 5;

    /**
     * Cards for the desktop “Projects” mega menu (horizontal carousel).
     *
     * @return array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>
     */
    public static function cards(): array
    {
        if (! Schema::hasTable('projects')) {
            return [];
        }

        $projects = Project::query()
            ->published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->limit(self::LIMIT)
            ->get();

        $out = [];
        foreach ($projects as $project) {
            $loc = $project->location ? trim((string) $project->location) : '';
            if ($loc === '' && $project->developer_name) {
                $loc = trim((string) $project->developer_name);
            }
            if ($loc === '') {
                $loc = 'Bangalore';
            }

            $out[] = [
                'url' => route('projects.show', $project),
                'title' => $project->title,
                'image' => $project->featuredBannerUrl(),
                'location' => $loc,
                'badge' => $project->is_featured ? 'Featured' : 'Project',
            ];
        }

        return $out;
    }
}
