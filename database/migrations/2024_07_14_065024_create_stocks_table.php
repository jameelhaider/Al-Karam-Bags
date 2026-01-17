<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();
            $table->string('model_id')->nullable();
            $table->string('color')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('qty')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('dealer_id')->nullable();
            $table->string('quality_status')->nullable();
            $table->string('model_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('status')->default('Available');
            $table->string('l_purchase_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
