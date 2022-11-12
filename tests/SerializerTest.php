<?php

namespace Spatie\Fractalistic\Test;

use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Test\TestClasses\TestTransformer;

class SerializerTest extends TestCase
{
    /** @test */
    public function it_does_not_generate_a_data_key_for_a_collection()
    {
        $array = $this->fractal
            ->collection($this->testBooks, new TestTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();

        $expectedArray = [
            ['id' => 1, 'author' => 'Philip K Dick'],
            ['id' => 2, 'author' => 'George R. R. Satan'],
        ];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_does_not_generate_a_data_key_for_an_item()
    {
        $array = $this->fractal
            ->item($this->testBooks[0], new TestTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();

        $expectedArray = ['id' => 1, 'author' => 'Philip K Dick'];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_accepts_a_class_name_for_the_serializer()
    {
        $array = $this->fractal
            ->item($this->testBooks[0], new TestTransformer())
            ->serializeWith(ArraySerializer::class)
            ->toArray();

        $expectedArray = ['id' => 1, 'author' => 'Philip K Dick'];

        $this->assertEquals($expectedArray, $array);
    }
}
