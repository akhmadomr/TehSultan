
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_totals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained();
            $table->decimal('income', 12, 2);
            $table->decimal('expense', 12, 2);
            $table->decimal('net', 12, 2);
            $table->decimal('total_income', 12, 2);
            $table->decimal('total_expense', 12, 2);
            $table->decimal('total_net', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_totals');
    }
};