<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriPosToItemmasterTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('itemmaster')) {
            Schema::table('itemmaster', function (Blueprint $table) {
                if (!Schema::hasColumn('itemmaster', 'KategoriPOS')) {
                    $table->string('KategoriPOS', 20)->nullable()->default('FNB');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('itemmaster')) {
            Schema::table('itemmaster', function (Blueprint $table) {
                if (Schema::hasColumn('itemmaster', 'KategoriPOS')) {
                    $table->dropColumn('KategoriPOS');
                }
            });
        }
    }
}
