<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $now = now();
        $samples = [
            ['What areas in Bangalore do you cover?', 'We focus on North Bangalore and key growth corridors, and support clients across the wider city for resale, new launches, and investment inventory.', 10],
            ['How quickly will you respond to my enquiry?', 'We aim to reply within one business day. For urgent briefs, mention your timeline in the message.', 20],
            ['Do you only work with pre-launch projects?', 'No — we help with resale, ready-to-move, and launch-stage inventory. Pre-launch is one of several lanes we cover.', 30],
        ];

        foreach ($samples as [$q, $a, $ord]) {
            DB::table('faqs')->insert([
                'question' => $q,
                'answer' => $a,
                'sort_order' => $ord,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
