<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    public function run()
    {
        DB::table('messages')->insert([
            ['name' => 'created_successfully', 'message' => 'Created successfully'],
            ['name' => 'updated_successfully', 'message' => 'Updated successfully'],
            ['name' => 'deleted_successfully', 'message' => 'Deleted successfully'],
            ['name' => 'action_failed', 'message' => 'An error occurred while performing the action'],
        ]);
    }
}
