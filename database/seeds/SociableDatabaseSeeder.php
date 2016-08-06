<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;

class SociableDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $output = $this->command->getOutput();
        $output->title('Starting Database Seed');

        //---------------- Create user database -------------
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        //---------------- Populate users -------------
        $users = factory(Humweb\Sociable\Tests\Stubs\User::class, 3)->create();

        Model::reguard();
    }

}
