<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        App\Models\Admin::truncate();
        $user = \App\Models\Admin::create([
            'name' => "Admin",
            'email' => 'admin@app.com',
            'password' => bcrypt('password'),
        ]);
    }
}
