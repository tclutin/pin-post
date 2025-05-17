<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Пейзажи'],
            ['name' => 'Портреты'],
            ['name' => 'Животные'],
            ['name' => 'Жизнь'],
            ['name' => 'Еда'],
            ['name' => 'Путешествия'],
            ['name' => 'Спорт'],
            ['name' => 'Мемы'],
            ['name' => 'Друзья'],
            ['name' => 'Искусство'],
            ['name' => 'Аниме/Манга'],
            ['name' => 'Буддизм'],
            ['name' => 'Эмо'],
            ['name' => 'Философия'],
            ['name' => 'Сессия-боль'],
        ]);
    }
}
