<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            ['section' => 'Dev Ops',
            'name' => 'Docker LAMP/LEMP stack',
            'complete' => 1],
            [
                'section' => 'Dev Ops',
                'name' => 'Docker compose',
                'complete' => 0
            ],
            [
                'section' => 'Dev Ops',
                'name' => 'Deployment',
                'complete' => 0
            ]
        ]);
    }
}
