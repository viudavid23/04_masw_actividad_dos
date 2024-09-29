<?php

namespace Database\Seeders;

use App\Models\Serie;
use Illuminate\Database\Seeder;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Serie::factory(5)->create();
        Serie::unguard();

        Serie::factory()->create([
            'id' => 1,
            'title' => 'THE DARK CHRONICLES',
            'synopsis' => 'UNA SERIE QUE NARRA LAS AVENTURAS DE UN GRUPO DE JóVENES QUE DESCUBREN UN MUNDO OCULTO LLENO DE MISTERIO Y PELIGRO.',
            'release_date' => '2015-09-25',
            'created_at' => '2024-04-08T08:30:00.000000Z',
            'updated_at' => '2024-04-10T09:00:00.000000Z', 
        ]);
        
        Serie::factory()->create([
            'id' => 2,
            'title' => 'GALAXY WARRIORS',
            'synopsis' => 'EN UN FUTURO DISTANTE, UN EQUIPO DE GUERREROS ESPACIALES LUCHA POR LA SUPERVIVENCIA DE LA HUMANIDAD CONTRA UNA AMENAZA ALIENíGENA.',
            'release_date' => '2015-03-15',
            'created_at' => '2024-04-12T14:45:00.000000Z',
            'updated_at' => '2024-04-13T15:00:00.000000Z', 
        ]);
        
        Serie::factory()->create([
            'id' => 3,
            'title' => 'MYSTIC FALLS',
            'synopsis' => 'LA TRANQUILA CIUDAD DE MYSTIC FALLS ESCONDE UN OSCURO SECRETO QUE CAMBIARá LA VIDA DE SUS HABITANTES PARA SIEMPRE.',
            'release_date' => '2020-10-10',
            'created_at' => '2024-04-15T11:15:00.000000Z',
            'updated_at' => '2024-04-16T11:45:00.000000Z', 
        ]);
        
        Serie::factory()->create([
            'id' => 4,
            'title' => 'LEGACY OF THE ANCIENT',
            'synopsis' => 'UNA SERIE éPICA QUE SIGUE EL VIAJE DE UN HéROE DESTINADO A RESTAURAR EL EQUILIBRIO ENTRE EL BIEN Y EL MAL EN UN MUNDO DIVIDIDO.',
            'release_date' => '2022-06-22',
            'created_at' => '2024-04-20T10:30:00.000000Z',
            'updated_at' => '2024-04-21T10:30:00.000000Z',
        ]);
        
        Serie::factory()->create([
            'id' => 5,
            'title' => 'SHADOWS OF THE PAST',
            'synopsis' => 'EL PASADO SIEMPRE ENCUENTRA LA FORMA DE VOLVER, Y EN ESTA SERIE, LOS FANTASMAS DEL AYER ACECHAN A AQUELLOS QUE INTENTAN ESCAPAR DE SU DESTINO.',
            'release_date' => '2021-11-05',
            'created_at' => '2024-04-25T10:00:00.000000Z',
            'updated_at' => '2024-04-26T10:05:00.000000Z', 
        ]);        

        Serie::reguard();
    }
}
