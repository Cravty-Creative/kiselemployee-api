<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParameterDetailTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('parameter_detail')) {
      Schema::create('parameter_detail', function (Blueprint $table) {
        $table->integer('id', true, true);
        $table->integer('param_id', false, true);
        $table->string('name');
        $table->string('detail')->nullable();
        $table->integer('score');
        $table->timestamps();
        $table->foreign('param_id')->references('id')->on('tipe_karyawan');
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
    Schema::dropIfExists('parameter_detail');
  }
}
