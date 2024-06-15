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
        Schema::create('TTransactions', function (Blueprint $table) {
            $table->bigIncrements('TransactionId');
            $table->unsignedBigInteger('UserFId');
            $table->unsignedBigInteger('BrancheFId'); 
            $table->unsignedBigInteger('UserTypeFId');
            $table->unsignedBigInteger('CurrencyFId');
            $table->integer('FromBranchId');
            $table->string('Number');
            $table->integer('Amount');
            $table->longText('Note')->nullable();
            $table->string('Response')->nullable();
            $table->boolean('Sent')->default(false);
            $table->string("DateMovemented")->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TTransactionRequests');
    }
};
