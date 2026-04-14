<?php

namespace App\Support;

use App\Models\Property;
use App\Models\Project;
use Illuminate\Support\Facades\Schema;

final class NewLaunchesMegaMenu
{
    private const LIMIT = 5;

    /**
     * Cards for desktop “New Launches” mega menu (properties + projects).
     *
     * @return array<int, array{url: string, title: string, image: ?string, location: string, badge: string, weight: int}>
     */
    public static function cards(): array
    {
        if (! Schema::hasTable('properties') && ! Schema::hasTable('projects')) {
            return [];
        }

        $out = [];

        if (Schema::hasTable('properties')) {
            $launches = Property::query()
                ->published()
                ->newLaunch()
                ->orderByDesc('is_featured')
                ->orderByDesc('sort_order')
                ->orderByDesc('updated_at')
                ->limit(self::LIMIT)
                ->get();

            foreach ($launches as $property) {
                $loc = collect([$property->locality, $property->city])->filter()->implode(', ');

                $out[] = [
                    'url' => route('properties.show', $property),
                    'title' => $property->title,
                    'image' => $property->featuredBannerUrl(),
                    'location' => $loc !== '' ? $loc : 'Bangalore',
                    'badge' => $property->is_featured ? 'Featured' : 'New launch',
                    'weight' => 2,
                ];
            }
        }

        if (Schema::hasTable('projects')) {
            $projectLaunches = Project::query()
                ->published()
                ->newLaunch()
                ->orderByDesc('is_featured')
                ->orderByDesc('sort_order')
                ->orderByDesc('updated_at')
                ->limit(self::LIMIT)
                ->get();

            foreach ($projectLaunches as $project) {
                $out[] = [
                    'url' => route('projects.show', $project),
                    'title' => $project->title,
                    'image' => $project->featuredBannerUrl(),
                    'location' => $project->location ?: ($project->developer_name ?: 'Bangalore'),
                    'badge' => $project->is_featured ? 'Featured project' : 'New launch project',
                    'weight' => 1,
                ];
            }
        }

        usort($out, fn ($a, $b) => ($b['weight'] <=> $a['weight']));
        $out = array_slice($out, 0, self::LIMIT);

        return array_map(function ($row) {
            unset($row['weight']);

            return $row;
        }, $out);
    }
}
