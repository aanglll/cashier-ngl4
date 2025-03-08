<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('user_priv', ['admin', 'officer', 'warehouse admin'])->after('password');
        $table->text('address')->after('user_priv');
        $table->string('phone', 15)->after('address');
        $table->string('status', 11)->after('phone');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['user_priv', 'address', 'phone', 'status']);
    });
}

};
