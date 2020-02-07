<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App\Console\Commands\ImportLocations;
use App\Console\Commands\ImportWards;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportCommandTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Location importer: invalid input
     */
    public function testLocationImportInvalidInput()
    {

        // Non text file
        $exitCode = Artisan::call("import:locations", ["file_path" => config("database.connections.sqlite.database")]);
        $this->assertEquals(ImportLocations::INVALID_INPUT, $exitCode);

        // Inexistent file
        $exitCode = Artisan::call("import:locations", ["file_path" => "inexistent_file.txt"]);
        $this->assertEquals(ImportLocations::INVALID_INPUT, $exitCode);

        // Empty file
        $emptyFilePath = tempnam(sys_get_temp_dir(), "clqei");
        $exitCode = Artisan::call("import:locations", ["file_path" => $emptyFilePath]);
        unlink($emptyFilePath);
        $this->assertEquals(ImportLocations::INVALID_INPUT, $exitCode);

    }

    /**
     * Location importer: success
     */
    public function testLocationImportSuccess()
    {

        $this->seed();

        $filePath = tempnam(sys_get_temp_dir(), "clqei");
        file_put_contents($filePath, "TEST LOCATION 1" . PHP_EOL, FILE_APPEND);
        file_put_contents($filePath, "TEST LOCATION 2" . PHP_EOL, FILE_APPEND);
        $exitCode = Artisan::call("import:locations", ["file_path" => $filePath]);
        unlink($filePath);

        $this->assertEquals(ImportLocations::SUCCESS, $exitCode);
        $this->assertDatabaseHas(
            "locations",
            [
                "id" => 2,
                "name" => "TEST LOCATION 1",
                "deleted_at" => null
            ]
        );
        $this->assertDatabaseHas(
            "locations",
            [
                "id" => 3,
                "name" => "TEST LOCATION 2",
                "deleted_at" => null
            ]
        );
        $this->assertDatabaseMissing("locations", ["id" => 4]);

    }

    /**
     * Ward importer: invalid input
     */
    public function testWardImportInvalidInput()
    {

        // Non text file
        $exitCode = Artisan::call("import:wards", ["file_path" => config("database.connections.sqlite.database")]);
        $this->assertEquals(ImportWards::INVALID_INPUT, $exitCode);

        // Inexistent file
        $exitCode = Artisan::call("import:wards", ["file_path" => "inexistent_file.txt"]);
        $this->assertEquals(ImportWards::INVALID_INPUT, $exitCode);

        // Empty file
        $emptyFilePath = tempnam(sys_get_temp_dir(), "clqei");
        $exitCode = Artisan::call("import:wards", ["file_path" => $emptyFilePath]);
        unlink($emptyFilePath);
        $this->assertEquals(ImportWards::INVALID_INPUT, $exitCode);

    }

    /**
     * Ward importer: success
     */
    public function testWardImportSuccess()
    {

        $this->seed();

        $filePath = tempnam(sys_get_temp_dir(), "clqei");
        file_put_contents($filePath, "TEST WARD 1" . PHP_EOL, FILE_APPEND);
        file_put_contents($filePath, "TEST WARD 2" . PHP_EOL, FILE_APPEND);
        $exitCode = Artisan::call("import:wards", ["file_path" => $filePath]);
        unlink($filePath);

        $this->assertEquals(ImportLocations::SUCCESS, $exitCode);
        $this->assertDatabaseHas(
            "wards",
            [
                "id" => 2,
                "name" => "TEST WARD 1",
                "deleted_at" => null
            ]
        );
        $this->assertDatabaseHas(
            "wards",
            [
                "id" => 3,
                "name" => "TEST WARD 2",
                "deleted_at" => null
            ]
        );
        $this->assertDatabaseMissing("wards", ["id" => 4]);

    }

}
