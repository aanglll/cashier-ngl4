<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('stock_in')->default(0);  // Jumlah stok masuk
            $table->integer('stock_out')->default(0); // Jumlah stok keluar
            $table->integer('current_stock')->default(0); // Stok saat ini
            $table->string('source')->nullable(); // Sumber perubahan (e.g., "purchase", "sale")
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};
