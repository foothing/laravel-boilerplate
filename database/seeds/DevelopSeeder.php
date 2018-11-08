<?php

class DevelopSeeder extends \Illuminate\Database\Seeder {

    public function run() {
        $roles = [
            ['name' => 'Administrator'],
            ['name' => 'Operator'],
            ['name' => 'Client'],
        ];

        \DB::table("roles")->insert($roles);

        $user1 = new \App\User();
        $user1->forceFill([
            'email' => 'developer@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('test'),
            'status' => 'active'
        ]);
        $user1->save();
        $user1->roles()->attach(\App\Role::where('name', 'Administrator')->first()->id);
    }

}