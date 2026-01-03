<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('delivered_at')->nullable()->after('status');
        });

        // Modify enum to include 'delivered' status
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'validated', 'refused', 'delivered') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivered_at');
        });

        // Revert enum to original values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'validated', 'refused') DEFAULT 'pending'");
    }
};
