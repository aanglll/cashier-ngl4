<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusDefaultInSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Mengubah kolom 'status' untuk memiliki nilai default 'Pending'
            $table->string('status')->default('Pending')->change();
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Jika migrasi di-rollback, kembalikan kolom 'status' tanpa default
            $table->string('status')->default(null)->change();
        });
    }
}
