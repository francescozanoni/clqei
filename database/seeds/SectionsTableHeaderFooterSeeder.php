<?php
declare(strict_types = 1);

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableHeaderFooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $sectionsHeadersFooters = json_decode(file_get_contents(database_path('seeds/sections_headers_footers_' . config('app.locale') . '.json')),
            true);

        foreach ($sectionsHeadersFooters as $index => $section) {
            DB::table(Section::getTableName())
                ->where('id', ($index + 1))
                ->update([
                    'header' => $section['header'],
                    'footer' => $section['footer'],
                ]);
        }

        // @todo ensure no problems derived from section order changed (via "position" field) or sections (soft-)deleted

    }
}
