<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('child_id'); 
            $table->string('title');
            $table->text('content');
            $table->timestamp('sent_at')->nullable(); 
            $table->timestamps();

            $table->foreign('child_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};

