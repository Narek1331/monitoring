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
        Schema::create('task_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->boolean('status')->default(true);
            $table->longText('text')->nullable();
            $table->integer('status_code')->nullable();
            $table->boolean('sended')->default(false);
            $table->integer('sended_count')->default(0);
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_messages');
    }
};
