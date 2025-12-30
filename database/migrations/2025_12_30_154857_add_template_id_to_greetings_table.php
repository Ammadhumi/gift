<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateIdToGreetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('greetings', function (Blueprint $table) {
            $table->foreignId('template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('greetings', function (Blueprint $table) {
            $table->foreignId('template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->after('user_id');
        });
    }
}
