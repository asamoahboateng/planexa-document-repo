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
        Schema::create('meeting_videos', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->text('video_title');
            $table->bigInteger('video_duration');
            $table->text('video_transcript');
            $table->text('video_description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_videos');
    }
};
