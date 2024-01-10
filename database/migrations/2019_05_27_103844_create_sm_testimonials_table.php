<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_testimonials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('designation');
            $table->string('institution_name');
            $table->string('image');
            $table->text('description');
            $table->integer('star_rating')->default(5);
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
        DB::table('sm_testimonials')->insert([
            [
                'name' => 'Tristique euhen',
                'designation' => 'CEO',
                'institution_name' => 'Google',
                'image' => 'public/uploads/staff/demo/staff.jpg',
                'description' => 'its vast! Infix has more additional feature that will expect in a complete solution.',
                'star_rating' => 5,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Malala euhen',
                'designation' => 'Chairman',
                'institution_name' => 'Linkdin',
                'image' => 'public/uploads/staff/demo/staff.jpg',
                'description' => 'its vast! Infix has more additional feature that will expect in a complete solution.',
                'star_rating' => 4,
                'created_at' => date('Y-m-d h:i:s')
            ],
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('sm_testimonials');
    }
}
