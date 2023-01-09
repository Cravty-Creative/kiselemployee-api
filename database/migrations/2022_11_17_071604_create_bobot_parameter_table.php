<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBobotParameterTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('bobot_parameter')) {
      Schema::create('bobot_parameter', function (Blueprint $table) {
        $table->integer('id', true, true);
        $table->integer('type_id', false, true);
        $table->integer('param_id', false, true);
        $table->integer('bobot');
        $table->integer('max');
        $table->decimal('max_x_bobot', 3, 2);
        $table->timestamps();
        $table->foreign('type_id')->references('id')->on('tipe_karyawan');
        $table->foreign('param_id')->references('id')->on('parameter');
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
    Schema::dropIfExists('bobot_parameter');
  }
}
