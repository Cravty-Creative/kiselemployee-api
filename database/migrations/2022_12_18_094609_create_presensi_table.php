<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('presensi')) {
      Schema::create('presensi', function (Blueprint $table) {
        $table->integer('id', true, true);
        $table->integer('user_id', false, true);
        $table->string('hari', 5);
        $table->time('jam_masuk');
        $table->time('jam_pulang')->nullable();
        $table->string('status', 45);
        $table->decimal('skor', 3, 2)->default(0.00);
        $table->timestamps();
        $table->string('created_by', 150);
        $table->string('updated_by', 150);
        $table->foreign('user_id')->references('id')->on('users');
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
    Schema::dropIfExists('presensi');
  }
}
