<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateHcPaymentTable
 */
class CreateHcPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('hc_payment', function (Blueprint $table) {
            $table->increments('count');
            $table->uuid('id')->unique();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('deleted_at')->nullable();

            $table->string('driver')->index();
            $table->enum('status', ['pending', 'completed', 'canceled'])->index();

            $table->double('amount', 20, 6)->index();
            $table->string('currency', 3)->default('EUR');

            $table->string('order_number')->unique();
            $table->json('order')->nullable();

            $table->string('reason')->nullable()->index();
            $table->string('method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_payment');
    }
}
