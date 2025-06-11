<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResidentConsultant;

class ResidentConsultantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResidentConsultant::create([
            'name' => 'Dr. Mohd Iqbal',
            'suite_number' => '301',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Chai Feng Yih',
            'suite_number' => '302',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Sivaharan',
            'suite_number' => '303',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. J.S. Sidhu',
            'suite_number' => '305',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Gordon Ma',
            'suite_number' => '308',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Lim Pitt Kent',
            'suite_number' => '309',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Shawin Periasamy',
            'suite_number' => '310',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Lum Chee Lun',
            'suite_number' => '311',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Frederick Chuo',
            'suite_number' => '312',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Tan Chee Kong',
            'suite_number' => '313',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Richard Ng',
            'suite_number' => '315',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Geetha Rajasing',
            'suite_number' => '316',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Joseph Lau',
            'suite_number' => '317',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Sutha Chelliah',
            'suite_number' => '318',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Grace Ng Huey Yuan',
            'suite_number' => '319',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Kumaraswami',
            'suite_number' => '323',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Goh Kheng Wee',
            'suite_number' => '329',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Pulivendhan',
            'suite_number' => '330',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Nahulan',
            'suite_number' => '331',
        ]);
        ResidentConsultant::create([
            'name' => 'Dr. Coo Ewe Chee',
            'suite_number' => '332',
        ]);

        ResidentConsultant::create([
            'name' => 'Dr. Logandran Vijayan Kumar',
            'suite_number' => 'L2EU',
        ]);

    }
}
