<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('user_id');
        
            $table->text('body');
            $table->timestamps();
        
            $table->foreign('job_id', 'comments_job_id_foreign')
                  ->references('id')->on('jobs')
                  ->cascadeOnDelete();
        
            $table->foreign('user_id', 'comments_user_id_foreign')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

