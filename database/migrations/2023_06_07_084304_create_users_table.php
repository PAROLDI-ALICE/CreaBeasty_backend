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
        Schema::disableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->boolean('is_admin')->default(1);
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('username', 50)->unique();
            $table->string('address1', 100);
            $table->string('address2', 50)->nullable();
            $table->string('phone', 20);
            $table->string('zipcode', 12);
            $table->string('city', 100);
            $table->string('country', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
