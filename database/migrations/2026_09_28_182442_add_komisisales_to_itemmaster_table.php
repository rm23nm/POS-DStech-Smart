<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKomisisalesToItemmasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itemmaster', function (Blueprint $table) {
            if (!Schema::hasColumn('itemmaster', 'KomisiSales')) {
                $table->double('KomisiSales')->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itemmaster', function (Blueprint $table) {
            if (Schema::hasColumn('itemmaster', 'KomisiSales')) {
                $table->dropColumn('KomisiSales');
            }
        });
    }
}
