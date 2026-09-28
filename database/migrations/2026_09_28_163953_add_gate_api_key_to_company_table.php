<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company', function (Blueprint $table) {
            // API Key unik per client - dipakai Gate Agent untuk autentikasi sync data
            $table->string('GateApiKey', 64)->nullable()->after('KodePartner');
        });
    }

    public function down(): void
    {
        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn('GateApiKey');
        });
    }
};
