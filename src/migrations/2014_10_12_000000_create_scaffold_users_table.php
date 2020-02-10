<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScaffoldUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scaffold_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->boolean('is_admin')->default(FALSE);
            $table->rememberToken();
            $table->timestamps();
        });

        $admin_users = [
            ['name'=>'Ryan Solida','email'=>'ryansolida@gmail.com','password'=>Hash::make('password'),'is_admin'=>TRUE]
        ];

        foreach ($admin_users as $au){
            $user = new \Sil\Scaffold\SilScaffoldUser();
            foreach ($au as $k=>$v){
                $user->$k = $v;
            }
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scaffold_users');
    }
}
