<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "name" => "Clothes", 
                "image" => "https://i.imgur.com/QkIa5tT.jpeg"
             ], 
          [
                   "name" => "Electronics", 
                   "image" => "https://i.imgur.com/ZANVnHE.jpeg"
                ], 
          [
                      "name" => "Furniture", 
                      "image" => "https://i.imgur.com/Qphac99.jpeg"
                   ], 
          [
                         "name" => "Shoes", 
                         "image" => "https://i.imgur.com/qNOjJje.jpeg"
                      ], 
          [
                            "name" => "Miscellaneous", 
                            "image" => "https://i.imgur.com/BG8J0Fj.jpg"
                         ] 
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'image' => $category['image']
            ]);
        }
    }
}
