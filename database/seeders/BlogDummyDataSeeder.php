<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'slug' => 'first-time-home-buyer-checklist-bangalore',
                'title' => 'First-time home buyer checklist in Bangalore',
                'excerpt' => 'Budgeting, location shortlisting, and legal diligence — a practical starter guide before you book a site visit.',
                'body' => <<<'HTML'
<p>Buying your first home in a fast-moving market can feel overwhelming. Start with a <strong>clear budget</strong> that includes registration, stamp duty, and interior fit-out — not just the base price.</p>
<p>Shortlist locations by commute, schools, and future metro or road projects. On every shortlist, verify <em>RERA registration</em>, builder track record, and loan pre-approval so you can move quickly on the right unit.</p>
<ul>
<li>Get a bank pre-approval letter</li>
<li>Compare carpet vs super built-up area</li>
<li>Read the sale agreement with a lawyer</li>
</ul>
<p>This is sample content for demos; replace with your own articles.</p>
HTML,
                'meta_title' => 'First-time home buyer checklist | PropUpdate Blog',
                'meta_description' => 'Sample blog post for PropUpdate — budgeting, location, and legal checks for Bangalore buyers.',
                'meta_keywords' => 'Bangalore, first home, RERA, checklist',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
            ],
            [
                'slug' => 'rent-vs-buy-2026-outlook',
                'title' => 'Rent vs buy: what to consider in 2026',
                'excerpt' => 'Interest rates, tenure, and mobility — when renting still wins, and when ownership pays off over the long run.',
                'body' => <<<'HTML'
<p>The rent-vs-buy debate depends on <strong>how long you will stay</strong> in one city and whether you value flexibility over equity building.</p>
<p>If your job or family plans may move you in two to three years, renting can avoid transaction costs and maintenance headaches. If you plan to stay five years or more, ownership often builds wealth — especially when rents rise steadily in your micro-market.</p>
<blockquote>Sample quote block for layout testing.</blockquote>
<p>Use a simple spreadsheet: compare EMI + maintenance vs rent + savings returns. This article is placeholder text for the blog listing and detail pages.</p>
HTML,
                'meta_title' => 'Rent vs buy 2026 | PropUpdate Blog sample',
                'meta_description' => 'Demo article comparing renting and buying — sample content for PropUpdate.',
                'meta_keywords' => 'rent, EMI, Bangalore real estate',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
            ],
            [
                'slug' => 'interior-trends-open-layouts-natural-light',
                'title' => 'Interior trends: open layouts and natural light',
                'excerpt' => 'How new projects are designing living spaces for work-from-home and weekend entertaining.',
                'body' => <<<'HTML'
<p>Developers are prioritising <strong>wider balconies</strong>, study nooks, and semi-open kitchens that photograph well and feel airy.</p>
<p>For end-users, focus on <em>storage</em> and acoustic comfort — open plans look great but need planning for calls and quiet work.</p>
<p>This dummy post includes enough HTML to test the blog detail template (paragraphs, lists, and formatting).</p>
<ul>
<li>Neutral palettes with one accent wall</li>
<li>LED layers instead of a single ceiling light</li>
<li>Indoor plants for humidity and noise softening</li>
</ul>
HTML,
                'meta_title' => 'Interior trends open layouts | Sample | PropUpdate',
                'meta_description' => 'Sample blog about interiors and natural light for PropUpdate demos.',
                'meta_keywords' => 'interiors, Bangalore apartments, design',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
            ],
            [
                'slug' => 'draft-post-not-published',
                'title' => 'Draft: upcoming market roundup (unpublished)',
                'excerpt' => 'This entry is intentionally unpublished to test draft behaviour in the admin and frontend.',
                'body' => '<p>Draft body — should not appear on the public blog when <code>is_published</code> is false.</p>',
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'featured_image_path' => null,
                'featured_image_url' => null,
                'is_published' => false,
            ],
        ];

        foreach ($rows as $row) {
            BlogPost::updateOrCreate(
                ['slug' => $row['slug']],
                array_merge($row, [
                    'published_at' => ($row['is_published'] ?? false) ? $now : null,
                ])
            );
        }
    }
}
