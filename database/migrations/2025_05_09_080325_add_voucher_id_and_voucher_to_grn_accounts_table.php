<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('voucher_id')->nullable()->after('id');
            
            $table->string('voucher')->nullable()->after('voucher_id');

            $table->foreign('voucher_id')->references('id')->on('voucher_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('grn_accounts', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['voucher_id', 'voucher']);
        });
    }
};
