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
    Schema::create('mas_jenis_tools', function (Blueprint $table) {
      $table->string('kd_jenis', 10);
      $table->string('nm_jenis');

      $table->primary('kd_jenis');
      $table->index('kd_jenis');
    });

    Schema::create('mas_tools', function (Blueprint $table) {
      $table->string('kd_tool', 50);
      $table->string('nm_tool', 150);
      $table->string('kd_jenis', 10)->nullable()->default('OTH');
      $table->integer('stok')->nullable()->default(0);
      $table->integer('stok_minimal')->nullable()->default(0);
      $table->integer('harga')->nullable()->default(0);
      $table->boolean('st_aktif')->nullable()->default(true);
      $table->boolean('st_sekali_pakai')->nullable()->default(false);
      $table->timestamps();

      $table->primary('kd_tool');
      $table->foreign('kd_jenis')
        ->references('kd_jenis')
        ->on('mas_jenis_tools')
        ->onUpdate('cascade')
        ->onDelete('restrict');

      $table->index(['kd_tool', 'kd_jenis', 'stok', 'stok_minimal', 'harga', 'st_aktif'], 'mas_tools_index');
    });

    Schema::create('mas_operators', function (Blueprint $table) {
      $table->id();
      $table->string('nm_operator', 100);
      $table->string('divisi', 100);
      $table->timestamps();

      $table->index(['id', 'nm_operator']);
    });

    Schema::create('tr_pinj_tool1s', function (Blueprint $table) {
      $table->string('kd_pinj', 13); /* PTWHS07230001 */
      $table->timestamp('tgl');
      $table->bigInteger('operator')->unsigned();
      $table->integer('status')->nullable()->default(0);
      $table->timestamps();

      $table->primary(['kd_pinj']);
      $table->foreign('operator')->references('id')->on('mas_operators')->cascadeOnUpdate()->restrictOnDelete();

      $table->index(['kd_pinj', 'tgl', 'operator', 'status']);
    });

    Schema::create('tr_pinj_tool2s', function (Blueprint $table) {
      $table->id();
      $table->string('kd_pinj', 13);
      $table->string('kd_tool', 50);
      $table->integer('qty');
      $table->timestamp('tgl_kembali')->nullable();

      $table->foreign('kd_pinj')->references('kd_pinj')->on('tr_pinj_tool1s')->cascadeOnDelete();
      $table->foreign('kd_tool')->references('kd_tool')->on('mas_tools')->cascadeOnDelete();
      $table->unique(['kd_pinj', 'kd_tool']);
      $table->index(['kd_pinj', 'kd_tool']);
    });

    Schema::create('tr_inout_tools', function (Blueprint $table) {
      $table->id();
      $table->string('kd_tool', 50);
      $table->date('tgl');
      $table->bigInteger('operator')->unsigned();
      $table->string('status', 10);
      $table->integer('qty');
      $table->integer('harga')->nullable();
      $table->timestamps();

      $table->foreign('kd_tool')->references('kd_tool')->on('mas_tools');
      $table->foreign('operator')->references('id')->on('mas_operators');
    });

    Schema::create('tr_pp_tool1s', function (Blueprint $table) {
      $table->string('no_pp', 13)->primary(); /* PPWHS07230001 */
      $table->date('tgl_pp');
      $table->string('keterangan')->nullable();
      $table->timestamp('tgl_approve')->nullable();
      $table->timestamp('submit_prch')->nullable();
      $table->timestamp('tgl_receive')->nullable();
      $table->string('file')->nullable();
      $table->timestamps();
    });

    Schema::create('tr_pp_tool2s', function (Blueprint $table) {
      $table->id();
      $table->string('no_pp', 13);
      $table->string('kd_tool', 50);
      $table->integer('qty');

      // $table->primary(['no_pp', 'kd_tool']);
      $table->unique(['no_pp', 'kd_tool']);
      $table->foreign('no_pp')->references('no_pp')->on('tr_pp_tool1s');
      $table->foreign('kd_tool')->references('kd_tool')->on('mas_tools');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('mas_tools');
    Schema::dropIfExists('mas_jenis_tools');
    Schema::dropIfExists('mas_operators');
    Schema::dropIfExists('tr_pinj_tool2s');
    Schema::dropIfExists('tr_pinj_tool1s');
  }
};
