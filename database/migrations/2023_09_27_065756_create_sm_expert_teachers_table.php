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
        Schema::create('sm_expert_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('image');
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });

        DB::table('sm_expert_teachers')->insert([
            [
                'name' => "Teacher 1",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
            [
                'name' => "Teacher 2",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
            [
                'name' => "Teacher 3",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
            [
                'name' => "Teacher 4",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
            [
                'name' => "Teacher 5",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
            [
                'name' => "Teacher 6",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
            [
                'name' => "Teacher 7",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
            [
                'name' => "Teacher 8",
                'designation' => "Designation",
                'image' => "public/uploads/expert_teacher/teacher-1.jpg",
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_expert_teachers');
    }
};
