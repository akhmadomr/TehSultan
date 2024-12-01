
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained();
            $table->decimal('total_income', 10, 2);
            $table->decimal('total_outcome', 10, 2);
            $table->decimal('net_income', 10, 2);
            $table->decimal('real_cash', 10, 2);
            $table->decimal('difference', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_summaries');
    }
};