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
        Schema::create('billing_records', function (Blueprint $table) {
            $table->unsignedInteger('billing_record_id', true);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('subscription_id');
            $table->integer('included_calls');
            $table->integer('extra_calls');
            $table->decimal('amount', 8, 2);
            $table->date('billed_at');
            $table->date('validity_start');
            $table->date('validity_end');
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_records');
    }
};
