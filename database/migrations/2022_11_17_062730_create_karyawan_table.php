<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('karyawan')) {
      Schema::create('karyawan', function (Blueprint $table) {
        $table->integer('id', true, true);
        $table->integer('user_id', false, true);
        $table->integer('type_id', false, true);
        $table->string('name')->nullable();
        $table->string('section')->nullable();
        $table->string('work_location')->nullable();
        $table->string('job_title')->nullable();
        $table->string('spv1_name')->nullable();
        $table->string('spv2_name')->nullable();
        $table->string('spv1_section')->nullable();
        $table->string('spv2_section')->nullable();
        $table->timestamps();
        $table->foreign('user_id')->references('id')->on('users');
        $table->foreign('type_id')->references('id')->on('tipe_karyawan');
      });
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('karyawan');
  }
}
