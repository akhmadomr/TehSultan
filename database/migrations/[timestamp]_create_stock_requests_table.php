<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained();
            $table->foreignId('stock_item_id')->constrained();
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->integer('request_amount');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('validated_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_requests');
    }
};
