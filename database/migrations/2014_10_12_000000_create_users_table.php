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
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('username', 20)->unique();
      $table->string('name', 150);
      $table->string('email', 150)->unique();
      $table->string('password');
      $table->rememberToken();
      $table->timestamps();

      $table->index(['id', 'username', 'email']);
    });

    /* Roles */
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100)->unique();
      $table->string('display_name', 150);
      $table->string('description', 150)->nullable();
      $table->timestamps();

      $table->index(['id', 'name', 'display_name']);
    });

    /* Permissions */
    Schema::create('permissions', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100)->unique();
      $table->string('display_name', 150);
      $table->string('description', 150)->nullable();
      $table->timestamps();

      $table->index(['id', 'name', 'display_name']);
    });

    /* User to Role */
    Schema::create('role_user', function (Blueprint $table) {
      $table->bigInteger('role_id')->unsigned();
      $table->bigInteger('user_id')->unsigned();

      $table->primary(['role_id', 'user_id']);
      $table->foreign('role_id')->references('id')->on('roles');
      $table->foreign('user_id')->references('id')->on('users');

      $table->index(['role_id', 'user_id']);
    });

    /* Role to Permission */
    Schema::create('permission_role', function (Blueprint $table) {
      $table->bigInteger('permission_id')->unsigned();
      $table->bigInteger('role_id')->unsigned();

      $table->primary(['permission_id', 'role_id']);
      $table->foreign('permission_id')->references('id')->on('permissions');
      $table->foreign('role_id')->references('id')->on('roles');

      $table->index(['permission_id', 'role_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
    Schema::dropIfExists('roles');
    Schema::dropIfExists('permissions');
    Schema::dropIfExists('role_user');
    Schema::dropIfExists('permission_role');
  }
};
