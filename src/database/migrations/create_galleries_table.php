<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');
            $table->string('collect');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_ext');
            $table->json('manipulations');
            $table->string('size');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
        });
    }
};
