<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoverPrintingQueuesTable extends Migration
{
    public function up()
    {
        Schema::create('cover_printing_queues', function (Blueprint $table) {
            $table->id();

            $table->string('order_id');
            $table->string('ordered_book_id');
            $table->string('status')->default('In Queue');
            $table->unsignedBigInteger('handled_by');

            $table->timestamps();

            $table->index('order_id');
            $table->index('ordered_book_id');
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('handled_by')->references('id')->on('employees')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cover_printing_queues');
    }
}
