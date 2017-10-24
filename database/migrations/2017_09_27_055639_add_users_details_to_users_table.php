<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersDetailsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->string('user_id')->after('id');
          $table->bigInteger('phone')->nullable()->after('email');
          $table->string('address')->nullable()->after('password');
          $table->integer('user_role_id')->unsigned()->after('user_id');
          $table->foreign('user_role_id')->references('id')->on('user_role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropForeign('users_user_role_id_foreign');
            $table->dropColumn('user_role_id');
        });
    }
}
