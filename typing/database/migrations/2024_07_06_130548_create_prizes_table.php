<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrizesTable extends Migration
{
    public function up()
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id'); // group_idカラムを追加
            $table->string('name');
            $table->decimal('probability', 8, 5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prizes');
    }
}
