<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Person::factory(10)->create();
        Person::unguard();

        Person::factory()->create([
            'id' => 1,
            'document_number' => '1015463A',
            'first_name' => 'JOHN',
            'last_name' => 'DOE',
            'birthdate' => '1985-02-15',
            'country_id' => 3
        ]);

        Person::factory()->create([
            'id' => 2,
            'document_number' => '2116574A',
            'first_name' => 'JANE',
            'last_name' => 'SMITH',
            'birthdate' => '1990-07-22',
            'country_id' => 7
        ]);
        
        Person::factory()->create([
            'id' => 3,
            'document_number' => '3227685A',
            'first_name' => 'MICHAEL',
            'last_name' => 'JOHNSON',
            'birthdate' => '1978-11-30',
            'country_id' => 2
        ]);
        
        Person::factory()->create([
            'id' => 4,
            'document_number' => '4338796A',
            'first_name' => 'EMILY',
            'last_name' => 'DAVIS',
            'birthdate' => '1988-05-10',
            'country_id' => 5
        ]);
        
        Person::factory()->create([
            'id' => 5,
            'document_number' => '5449807A',
            'first_name' => 'CHRIS',
            'last_name' => 'BROWN',
            'birthdate' => '1992-09-18',
            'country_id' => 8
        ]);

        Person::factory()->create([
            'id' => 6,
            'document_number' => '6550918B',
            'first_name' => 'KAREN',
            'last_name' => 'WILSON',
            'birthdate' => '1983-04-25',
            'country_id' => 1
        ]);
        
        Person::factory()->create([
            'id' => 7,
            'document_number' => '7661029B',
            'first_name' => 'DAVID',
            'last_name' => 'MILLER',
            'birthdate' => '1986-12-06',
            'country_id' => 4
        ]);
        
        Person::factory()->create([
            'id' => 8,
            'document_number' => '8772130B',
            'first_name' => 'SARA',
            'last_name' => 'MOORE',
            'birthdate' => '1991-03-19',
            'country_id' => 10
        ]);
        
        Person::factory()->create([
            'id' => 9,
            'document_number' => '9883241B',
            'first_name' => 'JAMES',
            'last_name' => 'TAYLOR',
            'birthdate' => '1975-08-02',
            'country_id' => 6
        ]);
        
        Person::factory()->create([
            'id' => 10,
            'document_number' => '1994352B',
            'first_name' => 'LINDA',
            'last_name' => 'ANDERSON',
            'birthdate' => '1980-10-14',
            'country_id' => 9
        ]);
        
        Person::factory()->create([
            'id' => 11,
            'document_number' => '2005463C',
            'first_name' => 'ROBERT',
            'last_name' => 'MARTINEZ',
            'birthdate' => '1982-01-23',
            'country_id' => 2
        ]);
        
        Person::factory()->create([
            'id' => 12,
            'document_number' => '3116574C',
            'first_name' => 'NANCY',
            'last_name' => 'THOMAS',
            'birthdate' => '1993-06-12',
            'country_id' => 5
        ]);
        
        Person::factory()->create([
            'id' => 13,
            'document_number' => '4227685C',
            'first_name' => 'KEVIN',
            'last_name' => 'HARRIS',
            'birthdate' => '1979-11-05',
            'country_id' => 7
        ]);
        
        Person::factory()->create([
            'id' => 14,
            'document_number' => '5338796C',
            'first_name' => 'LUCY',
            'last_name' => 'CLARK',
            'birthdate' => '1987-09-27',
            'country_id' => 3
        ]);
        
        Person::factory()->create([
            'id' => 15,
            'document_number' => '6449807C',
            'first_name' => 'PETER',
            'last_name' => 'LEWIS',
            'birthdate' => '1995-04-08',
            'country_id' => 4
        ]);        

        Person::reguard();
    }
}
