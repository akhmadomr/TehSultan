<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropColumn('user_id');
            
            // Make sure these columns exist
            if (!Schema::hasColumn('stock_requests', 'requested_by')) {
                $table->foreignId('requested_by')->constrained('users');
            }
            if (!Schema::hasColumn('stock_requests', 'validated_by')) {
                $table->foreignId('validated_by')->nullable()->constrained('users');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->dropConstrainedForeignId('requested_by');
            $table->dropConstrainedForeignId('validated_by');
        });
    }
};
