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
        Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // vendeur
        $table->decimal('total_amount', 12, 2);
        $table->decimal('paid_amount', 12, 2);
        $table->decimal('change_amount', 12, 2)->default(0);
        $table->string('payment_method'); // cash, mobile_money, card
        $table->text('notes')->nullable();
        $table->timestamp('sold_at')->useCurrent();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
