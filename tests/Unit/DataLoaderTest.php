<?php

namespace Tests\Unit;

use App\DataLoader;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DataLoaderTest extends TestCase
{

    public function testGetAssociativeArrayFromCSV()
    {
        $filename = 'test.csv';
        $fileContents = "test,abc\n123,456\n789,123\n456,789";
        $expectedArray = [
            ['test' => '123', 'abc' => '456'],
            ['test' => '789', 'abc' => '123'],
            ['test' => '456', 'abc' => '789']
        ];

        Storage::shouldReceive('disk->exists')->with($filename)->once()->andReturnTrue();
        Storage::shouldReceive('disk->get')->with($filename)->once()->andReturn($fileContents);

        $result = DataLoader::getAssociativeArrayFromCSV($filename);
        $this->assertEquals($expectedArray, $result);
    }
}
