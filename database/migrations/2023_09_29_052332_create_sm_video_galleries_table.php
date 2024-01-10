<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sm_video_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('video_link')->nullable();
            $table->boolean('is_publish')->default(true);
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('sm_video_galleries')->insert([
            [
                'name' => "One Click Update Infix Application",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.",
                'video_link' => "https://www.youtube.com/watch?v=4zR-uaZjZ2U",
            ],
            [
                'name' => "WhatsApp Chat Laravel Application",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.",
                'video_link' => "https://www.youtube.com/watch?v=k61cLi1_Zd0&ab_channel=Infixdev",
            ],
            [
                'name' => "Infix Module Installation",
                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.",
                'video_link' => "https://www.youtube.com/watch?v=4zR-uaZjZ2U&ab_channel=Infixdev",
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_video_galleries');
    }
};
