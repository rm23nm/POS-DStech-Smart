<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSyncedToFpenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {
        if (Schema::hasTable('fpenjualan')) {
            Schema::table('fpenjualan', function (Blueprint $table) {
                if (!Schema::hasColumn('fpenjualan', 'is_synced')) {
                    $table->tinyInteger('is_synced')->default(0)->after('Status');
                }
            });
        }
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
        if (Schema::hasTable('fpenjualan')) {
            Schema::table('fpenjualan', function (Blueprint $table) {
                if (Schema::hasColumn('fpenjualan', 'is_synced')) {
                    $table->dropColumn('is_synced');
                }
            });
        }
    }
        });
    }
}

