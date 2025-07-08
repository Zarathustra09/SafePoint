<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('crime_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('address')->nullable();
            $table->enum('status', ['pending', 'under_investigation', 'resolved', 'closed'])->default('pending');
            $table->timestamp('incident_date');
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index('severity');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crime_reports');
    }
};
