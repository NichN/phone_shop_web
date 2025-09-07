<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_item', function (Blueprint $table) {
            // Add columns only if they do not exist
            if (!Schema::hasColumn('product_item', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (!Schema::hasColumn('product_item', 'is_new')) {
                $table->boolean('is_new')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_item', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('product_item', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            if (Schema::hasColumn('product_item', 'is_new')) {
                $table->dropColumn('is_new');
            }
        });
    }
};
