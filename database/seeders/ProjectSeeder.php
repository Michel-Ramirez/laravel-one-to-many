<?php

namespace Database\Seeders;

use App\Models\Project;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Generator $faker): void
    {
        for ($i = 0; $i <= 20; $i++) {
            $project = new Project();
            $project->title = $faker->text(20);
            // $project->slug = str_replace(' ', '-', strtolower($project->title));
            $project->slug = Str::slug($project->title, '-');
            $project->description = $faker->paragraphs(2, true);
            $project->image = $faker->imageUrl(250, 250);
            $project->save();
        }
    }
}
