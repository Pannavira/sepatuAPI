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
    Schema::table('payments', function (Blueprint $table) {
        $table->timestamps(); // adds both created_at and updated_at
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropTimestamps();
    });
}
}; ?>

