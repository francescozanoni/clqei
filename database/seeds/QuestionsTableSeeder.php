<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // @todo move to an external file, maybe JSON

        // Section 1
        DB::table('questions')->insert([
            'id' => 1,
            'text' => 'Il tutor* ha esplicitato i ragionamenti che sottendevano le decisioni assistenziali',
            'section_id' => 1,
        ]);
        DB::table('questions')->insert([
            'id' => 2,
            'text' => 'Il tutor* mi poneva domande che mi aiutavano nel ragionamento clinico',
            'section_id' => 1,
        ]);
        DB::table('questions')->insert([
            'id' => 3,
            'text' => 'Ho avuto la possibilità di condividere con il tutor* le emozioni provate durante l\'esperienza di tirocinio',
            'section_id' => 1,
        ]);
        DB::table('questions')->insert([
            'id' => 4,
            'text' => 'Il tutor* ha mediato la mia relazione con i pazienti/famigliari quando la situazione era difficile',
            'section_id' => 1,
        ]);
        DB::table('questions')->insert([
            'id' => 5,
            'text' => 'Il tutor* era entusiasta di insegnarmi la pratica infermieristica',
            'section_id' => 1,
        ]);
        DB::table('questions')->insert([
            'id' => 6,
            'text' => 'Nella valutazione finale, il tutor* è stato/a coerente con i feedback che mi ha fornito durante il tirocinio',
            'section_id' => 1,
        ]);

        // Section 2
        DB::table('questions')->insert([
            'id' => 7,
            'text' => 'Ho percepito fiducia nei miei confronti',
            'section_id' => 2,
        ]);
        DB::table('questions')->insert([
            'id' => 8,
            'text' => 'Ho potuto sperimentarmi in autonomia nelle attività',
            'section_id' => 2,
        ]);
        DB::table('questions')->insert([
            'id' => 9,
            'text' => 'Mi è stato affidato un adeguato livello di responsabilità',
            'section_id' => 2,
        ]);
        DB::table('questions')->insert([
            'id' => 10,
            'text' => 'Ho avuto la possibilità di esprimere le mie opinioni e riflessioni critiche',
            'section_id' => 2,
        ]);
        DB::table('questions')->insert([
            'id' => 11,
            'text' => 'Mi sono sentito/a rispettato/a come studente',
            'section_id' => 2,
        ]);
        DB::table('questions')->insert([
            'id' => 12,
            'text' => 'Sono stato incoraggiato/a nei momenti di difficoltà',
            'section_id' => 2,
        ]);

        // Section 3
        DB::table('questions')->insert([
            'id' => 13,
            'text' => 'Gli infermieri avevano buoni standard di pratica professionale',
            'section_id' => 3,
        ]);
        DB::table('questions')->insert([
            'id' => 14,
            'text' => 'Era garantita la sicurezza dei pazienti/residenti/ospiti',
            'section_id' => 3,
        ]);
        DB::table('questions')->insert([
            'id' => 15,
            'text' => 'I dispositivi di protezione individuali e di sicurezza erano accessibili',
            'section_id' => 3,
        ]);
        DB::table('questions')->insert([
            'id' => 16,
            'text' => 'Gli infermieri mostravano passione per la professione',
            'section_id' => 3,
        ]);

        // Section 4
        DB::table('questions')->insert([
            'id' => 17,
            'text' => 'Mi sono stati offerti incontri sui miei bisogni di apprendimento',
            'section_id' => 4,
        ]);
        DB::table('questions')->insert([
            'id' => 18,
            'text' => 'Sono stato/a sollecitato/a ad elaborare il mio piano di autoapprendimento',
            'section_id' => 4,
        ]);
        DB::table('questions')->insert([
            'id' => 19,
            'text' => 'Sono stato/a sollecitato/a ad auto-valutarmi',
            'section_id' => 4,
        ]);

        // Section 5
        DB::table('questions')->insert([
            'id' => 20,
            'text' => 'Questa sede è stata per me un buon ambiente di apprendimento',
            'section_id' => 5,
        ]);
        DB::table('questions')->insert([
            'id' => 21,
            'text' => 'Complessivamente sono soddisfatto/a della mia esperienza di tirocinio',
            'section_id' => 5,
        ]);
        DB::table('questions')->insert([
            'id' => 22,
            'text' => 'Vorrei tornare un giorno in questo contesto a lavorare',
            'section_id' => 5,
        ]);
    }
}
