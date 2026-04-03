<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('summary');
            $table->text('description')->nullable();
            $table->string('icon_class', 120)->nullable()->comment('e.g. fa-solid fa-building');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $now = now();
        $rows = [
            ['Resale advisory', 'End-to-end support for buying and selling resale homes in Bangalore — pricing, diligence, and closure.', 'fa-solid fa-house-chimney', 10],
            ['New launch access', 'Early pricing, floor plans, and inventory before public launch — with clear milestone tracking.', 'fa-solid fa-rocket', 20],
            ['Investment consulting', 'Portfolio-aligned picks across micro-markets, with stress on approvals and realistic yield.', 'fa-solid fa-chart-line', 30],
            ['Documentation & legal', 'Title review, agreement checks, and coordination with counsel so you transact with confidence.', 'fa-solid fa-file-contract', 40],
        ];

        foreach ($rows as [$name, $summary, $icon, $ord]) {
            DB::table('services')->insert([
                'name' => $name,
                'summary' => $summary,
                'description' => null,
                'icon_class' => $icon,
                'sort_order' => $ord,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
