<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedBooksTable extends Migration
{
    public function up()
    {
        Schema::create('ordered_books', function (Blueprint $table) {
            $table->id();

            $table->string('order_id'); // FK to orders numeric id
            $table->string('ordered_book_id')->unique(); // e.g. orderID-B-001

            $table->string('book_name');
            $table->string('book_author')->nullable();
            $table->enum('binding_type', ['Paperback', 'Hardcover', 'Spiral'])->default('Paperback');
            $table->enum('lamination_type', ['Glossy', 'Matt'])->default('Matt');
            $table->text('special_note')->nullable();
            $table->string('book_pdf')->nullable();
            $table->boolean('custom_cover')->default(false);
            $table->string('cover')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('qty')->default(1);

            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ordered_books');
    }
}
