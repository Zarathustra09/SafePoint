<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('start_lat', 10, 8);
            $table->decimal('start_lng', 11, 8);
            $table->decimal('end_lat', 10, 8);
            $table->decimal('end_lng', 11, 8);
            $table->string('start_address');
            $table->string('end_address');
            $table->longText('polyline');
            $table->decimal('safety_score', 3, 2)->nullable();
            $table->string('duration')->nullable();
            $table->string('distance')->nullable();
            $table->json('crime_analysis')->nullable();
            $table->boolean('is_safer_route')->default(false);
            $table->string('route_type')->default('regular'); // 'regular' or 'safer'
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_routes');
    }
};
