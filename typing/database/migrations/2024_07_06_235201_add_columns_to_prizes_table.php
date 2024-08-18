<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->after('id');
            $table->unsignedBigInteger('sub_id')->nullable()->after('type_id');
            $table->text('notes')->nullable()->after('sub_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn('type_id');
            $table->dropColumn('sub_id');
            $table->dropColumn('notes');
        });
    }
}
