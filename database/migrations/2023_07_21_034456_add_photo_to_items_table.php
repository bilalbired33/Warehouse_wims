<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }

    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('photo')->nullable();
        });
    }
};
