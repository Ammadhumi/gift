<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('theme', 40)->default('spark');
            $table->string('font_family', 120)->default('Playfair Display');
            $table->text('intro_title')->nullable();
            $table->text('intro_subtitle')->nullable();
            $table->text('cake_title')->nullable();
            $table->text('cake_subtitle')->nullable();
            $table->text('album_title')->nullable();
            $table->text('album_subtitle')->nullable();
            $table->text('final_title')->nullable();
            $table->text('final_subtitle')->nullable();
            $table->string('video_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
