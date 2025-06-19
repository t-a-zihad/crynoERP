<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagingQueuesTable extends Migration
{
    public function up()
    {
        Schema::create('packaging_queues', function (Blueprint $table) {
            $table->id();

            $table->string('order_id');
            $table->string('status')->default('In Queue');
            $table->unsignedBigInteger('handled_by');

            $table->timestamps();

            $table->index('order_id');
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('handled_by')->references('id')->on('employees')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('packaging_queues');
    }
}
