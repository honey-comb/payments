<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('deleted_at')->nullable();

            $table->uuid('id')->unique();
            $table->enum('status', ['pending', 'completed', 'canceled'])->index();
            $table->string('order_number')->unique();
            $table->string('reason_id')->index();
            $table->string('method_id')->index();
            $table->string('payment_type')->nullable();
            $table->string('payment_id')->nullable();
            $table->double('amount', 20, 6)->index();
            $table->string('currency')->nullable();
            $table->text('configuration_value')->nullable();
            $table->uuid('invoice_id')->nullable();

            $table->foreign('reason_id')
                ->references('id')
                ->on('hc_payment_reason');

            $table->foreign('method_id')
                ->references('id')
                ->on('hc_payment_method');
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
