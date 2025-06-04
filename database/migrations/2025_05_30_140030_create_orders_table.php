

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_id')->unique(); // e.g. CRY_O-2025-05-00001
            $table->timestamp('order_date')->useCurrent();
            $table->enum('order_priority', ['High', 'Normal', 'Low']);
            $table->string('customer_name');
            $table->string('phone_number')->nullable();;
            $table->text('shipping_address');
            $table->enum('delivery_type', ['Free', 'Inside Dhaka', 'Outside Dhaka']);
            $table->decimal('delivery_charge', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->text('order_note')->nullable();
            $table->unsignedBigInteger('handled_by'); // employee id, role: order manager

            $table->timestamps();

            $table->foreign('handled_by')->references('id')->on('employees')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
