<?php

namespace App\Tests\Entity;

use App\Entity\Weather;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['0', 32],
            ['25', 77],
            ['37.5', 99.5],
            ['-5.5', 22.1],
            ['75.5', 167.9],
            ['-12.4', 9.68],
            ['20.3', 68.54]
        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $weather = new Weather();

        $weather->setTemperature($celsius);

        $this->assertEquals($expectedFahrenheit, $weather->getFahrenheit());
    }
}
