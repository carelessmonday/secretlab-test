<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectsValuesTable extends Migration {

    public function up(): void
    {
        Schema::create('object_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('object_key', 100)->index();
            $table->text('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('object_values');
    }
}
