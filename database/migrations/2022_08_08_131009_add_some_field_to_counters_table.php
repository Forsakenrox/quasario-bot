<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldToCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->integer('wins')->unsigned()->nullable()->after('messages_count')->default(0);
            $table->integer('looses')->unsigned()->nullable()->after('messages_count')->default(0);
            $table->timestamp('last_attack_at')->nullable()->after('wins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('counters', function (Blueprint $table) {
            //
        });
    }
}
