<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductItemIdToImagesTableV2 extends Migration
{
    public function up()
    {
        Schema::table('image', function (Blueprint $table) {
            $table->unsignedBigInteger('product_item_id')->nullable()->after('id');

            $table->foreign('product_item_id')
                ->references('id')
                ->on('product_items')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('image', function (Blueprint $table) {
            $table->dropForeign(['product_item_id']);
            $table->dropColumn('product_item_id');
        });
    }
}

