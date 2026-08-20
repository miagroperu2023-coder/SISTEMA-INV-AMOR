<?php

namespace Database\Seeders;

use App\Models\SizeSet;
use Illuminate\Database\Seeder;

class SizeSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $letras = ['XS', 'S', 'M', 'L', 'XL'];
        foreach ($letras as $i => $valor) {
            SizeSet::create(['tipo' => 'letra', 'valor' => $valor, 'orden' => $i]);
        }

        $numeros = ['26', '28', '30', '32', '34', '36', '38'];
        foreach ($numeros as $i => $valor) {
            SizeSet::create(['tipo' => 'numero', 'valor' => $valor, 'orden' => $i]);
        }
    }
}
