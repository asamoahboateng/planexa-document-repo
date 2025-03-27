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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->string('old_address')->nullable();
            $table->string('slug');
            $table->string('postal_code')->nullable();
            $table->text('province');
            $table->text('ward')->nullable();
            $table->float('lat')->nullable();
            $table->float('long')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }

    public function createdby(): belongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function updatedby(): belongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
};
