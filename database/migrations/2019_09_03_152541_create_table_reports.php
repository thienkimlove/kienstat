<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->text('content')->nullable();
            $table->string('date');
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->unsignedInteger('quantity')->default(0);
            $table->string('seller')->nullable();
            $table->text('note')->nullable();
            $table->unsignedSmallInteger('status')->default(0);


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
        Schema::dropIfExists('reports');
    }
}
