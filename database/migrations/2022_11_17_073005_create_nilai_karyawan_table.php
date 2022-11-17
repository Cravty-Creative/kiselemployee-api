<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiKaryawanTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('nilai_karyawan')) {
      Schema::create('nilai_karyawan', function (Blueprint $table) {
        $table->integer('id', true, true);
        $table->integer('emp_id', false, true);
        $table->integer('param_id', false, true);
        $table->integer('bobot_param_id', false, true);
        $table->decimal('nilai', 2, 2);
        $table->decimal('nilai_x_bobot', 2, 2);
        $table->string('periode')->nullable();
        $table->timestamps();
        $table->foreign('emp_id')->references('id')->on('karyawan');
        $table->foreign('param_id')->references('id')->on('parameter');
        $table->foreign('bobot_param_id')->references('id')->on('bobot_parameter');
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
    Schema::dropIfExists('nilai_karyawan');
  }
}
