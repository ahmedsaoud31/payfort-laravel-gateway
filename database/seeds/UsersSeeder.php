<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		User::create(
			[
				'name' => 'test',
				'email' => 'test@test.test',
				'password' => Hash::make('123456'),
				'phone' => '01148024524',
				'company_name' => 'CompanyName',
				'percentage_fee' => 2.65,
				'percentage_fee' => 3,
			]
		);
    }
}
