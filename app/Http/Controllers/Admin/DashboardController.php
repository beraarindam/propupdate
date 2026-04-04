<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Enquiry;
use App\Models\GalleryItem;
use App\Models\Property;
use App\Models\PropertyCategory;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $propertiesTotal = Property::query()->count();
        $propertiesLive = Property::query()->published()->count();
        $propertiesDraft = $propertiesTotal - $propertiesLive;

        $enquiriesTotal = Enquiry::query()->count();
        $enquiriesUnread = Enquiry::query()->whereNull('read_at')->count();

        $categoriesPublished = PropertyCategory::query()->where('is_published', true)->count();
        $galleryPublished = GalleryItem::query()->where('is_published', true)->count();
        $blogPublished = BlogPost::query()->where('is_published', true)->count();

        $enquiryChartLabels = [];
        $enquiryChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $enquiryChartLabels[] = $start->format('M Y');
            $enquiryChartData[] = Enquiry::query()
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        $categoryBuckets = Property::query()
            ->select('property_category_id')
            ->selectRaw('count(*) as c')
            ->groupBy('property_category_id')
            ->orderByDesc('c')
            ->limit(6)
            ->get();

        $categoryIds = $categoryBuckets->pluck('property_category_id')->filter()->unique()->values();
        $categoryNames = PropertyCategory::query()
            ->whereIn('id', $categoryIds)
            ->pluck('name', 'id');

        $categoryLabels = [];
        $categoryData = [];
        foreach ($categoryBuckets as $row) {
            $categoryLabels[] = $row->property_category_id !== null
                ? (string) ($categoryNames[$row->property_category_id] ?? 'Category #'.$row->property_category_id)
                : 'Uncategorised';
            $categoryData[] = (int) $row->c;
        }

        if ($categoryLabels === []) {
            $categoryLabels = ['No listings yet'];
            $categoryData = [0];
        }

        $recentEnquiries = Enquiry::query()
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('backend.dashboard', [
            'propertiesTotal' => $propertiesTotal,
            'propertiesLive' => $propertiesLive,
            'propertiesDraft' => $propertiesDraft,
            'enquiriesTotal' => $enquiriesTotal,
            'enquiriesUnread' => $enquiriesUnread,
            'categoriesPublished' => $categoriesPublished,
            'galleryPublished' => $galleryPublished,
            'blogPublished' => $blogPublished,
            'enquiryChartLabels' => $enquiryChartLabels,
            'enquiryChartData' => $enquiryChartData,
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData,
            'recentEnquiries' => $recentEnquiries,
        ]);
    }
}
