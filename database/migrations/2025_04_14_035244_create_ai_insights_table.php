<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('insight_date');
            $table->text('reflection_question');
            $table->text('user_response');
            $table->text('analysis');
            $table->json('recommendations');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_insights');
    }
};
