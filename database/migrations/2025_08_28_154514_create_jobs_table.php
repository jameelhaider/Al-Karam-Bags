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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('company_id');
            $table->string('company_name');
            $table->string('model_id');
            $table->string('model_name');
            $table->string('design_date')->nullable();
            $table->string('out_date')->nullable();
            $table->string('repair_status')->nullable();
            $table->text('issues')->nullable();
            $table->text('parts')->nullable();
            $table->string('advance');
            $table->string('status')->default('Inn');
            $table->string('reason')->nullable();
            $table->string('inn_date');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
