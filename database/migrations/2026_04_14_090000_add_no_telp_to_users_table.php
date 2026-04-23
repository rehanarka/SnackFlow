<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('user', 'no_telepon')) {
            Schema::table('user', function (Blueprint $table) {
                $table->string('no_telepon')->nullable()->after('avatar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user', 'no_telepon')) {
            Schema::table('user', function (Blueprint $table) {
                $table->dropColumn('no_telepon');
            });
        }
    }
};
