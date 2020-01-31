<?php

namespace Spatie\Fractalistic\Test;

use League\Fractal\Pagination\Cursor;
use League\Fractal\Serializer\JsonApiSerializer;
use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Fractal;

class FractalTest extends TestCase
{
    /** @test */
    public function it_can_transform_multiple_items_using_a_transformer_to_json()
    {
        $json = $this->fractal
            ->collection($this->testBooks, new TestTransformer())
            ->toJson();

        $expectedJson = '{"data":[{"id":1,"author":"Philip K Dick"},{"id":2,"author":"George R. R. Satan"}]}';

        $this->assertEquals($expectedJson, $json);
    }

    /** @test */
    public function it_can_accept_a_bitmask_when_converting_to_json()
    {
        $json = $this->fractal
            ->collection($this->testBooks, new TestTransformer())
            ->toJson(JSON_PRETTY_PRINT);

        $expectedJson = <<<'JSON'
{
    "data": [
        {
            "id": 1,
            "author": "Philip K Dick"
        },
        {
            "id": 2,
            "author": "George R. R. Satan"
        }
    ]
}
JSON;

        $this->assertEquals($expectedJson, $json);
    }

    /** @test */
    public function it_can_transform_multiple_items_using_a_transformer_to_an_array()
    {
        $array = $this->fractal
            ->collection($this->testBooks, new TestTransformer())
            ->toArray();

        $expectedArray = ['data' => [
            ['id' => 1, 'author' => 'Philip K Dick'],
            ['id' => 2, 'author' => 'George R. R. Satan'],
        ]];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_can_transform_a_collection_using_a_callback()
    {
        $array = $this->fractal
            ->collection($this->testBooks, function ($book) {
                return ['id' => $book['id']];
            })->toArray();

        $expectedArray = ['data' => [
            ['id' => 1],
            ['id' => 2],
        ]];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_can_transform_a_collection_using_a_class_name()
    {
        $json = $this->fractal
            ->collection($this->testBooks, TestTransformer::class)
            ->toJson();

        $expectedJson = '{"data":[{"id":1,"author":"Philip K Dick"},{"id":2,"author":"George R. R. Satan"}]}';

        $this->assertEquals($expectedJson, $json);
    }

    /** @test */
    public function it_provides_a_method_to_specify_the_transformer()
    {
        $array = $this->fractal
            ->collection($this->testBooks)
            ->transformWith(new TestTransformer())
            ->toArray();

        $expectedArray = ['data' => [
            ['id' => 1, 'author' => 'Philip K Dick'],
            ['id' => 2, 'author' => 'George R. R. Satan'],
        ]];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_can_accept_a_class_name_as_a_transformer()
    {
        $array = $this->fractal
            ->collection($this->testBooks)
            ->transformWith(TestTransformer::class)
            ->toArray();

        $expectedArray = ['data' => [
            ['id' => 1, 'author' => 'Philip K Dick'],
            ['id' => 2, 'author' => 'George R. R. Satan'],
        ]];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_can_perform_a_single_item()
    {
        $array = $this->fractal
            ->item($this->testBooks[0], new TestTransformer())
            ->toArray();

        $expectedArray = ['data' => [
            'id' => 1, 'author' => 'Philip K Dick', ]];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_can_create_a_resource()
    {
        $resource = $this->fractal
            ->collection($this->testBooks, new TestTransformer())
            ->getResource();

        $this->assertInstanceOf(\League\Fractal\Resource\ResourceInterface::class, $resource);
    }

    /** @test */
    public function it_can_create_fractal_data()
    {
        $resource = $this->fractal
            ->collection($this->testBooks, new TestTransformer())
            ->createData();

        $this->assertInstanceOf(\League\Fractal\Scope::class, $resource);
    }

    /** @test */
    public function it_can_perform_a_null_item()
    {
        $array = Fractal::create(null, NullableTransformer::class)
            ->serializeWith(new JsonApiSerializer())
            ->withResourceName('books')
            ->toArray();

        $expectedArray = ['data' => null];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_can_create_a_null_resource()
    {
        $resource = Fractal::create(null, NullableTransformer::class)
            ->serializeWith(new JsonApiSerializer())
            ->withResourceName('books')
            ->getResource();

        $this->assertInstanceOf(\League\Fractal\Resource\ResourceInterface::class, $resource);
    }

    /** @test */
    public function it_can_create_null_fractal_data()
    {
        $resource = Fractal::create(null, NullableTransformer::class)
            ->serializeWith(new JsonApiSerializer())
            ->withResourceName('books')
            ->createData();

        $this->assertInstanceOf(\League\Fractal\Scope::class, $resource);
    }

    /** @test */
    public function it_provides_chainable_methods()
    {
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->item('test'));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->collection([]));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->primitive(123));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->transformWith(function () {
        }));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->serializeWith(new ArraySerializer()));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->withCursor(
            new Cursor(0, null, 10, 10)
        ));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->addMeta([]));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->paginateWith(
            $this->createMock('League\Fractal\Pagination\PaginatorInterface')
        ));
    }

    /** @test */
    public function it_can_perform_an_empty_array()
    {
        $array = Fractal::create([], TestTransformer::class)
            ->toArray();

        $expectedArray = ['data' => []];

        $this->assertEquals($expectedArray, $array);
    }

    /** @test */
    public function it_can_define_collection_after_resource_name()
    {
        $resource = Fractal::create()
            ->withResourceName('tests')
            ->collection($this->testBooks)
            ->transformWith(new TestTransformer);

        $this->assertEquals('tests', $resource->getResource()->getResourceKey());
    }

    /** @test */
    public function it_can_define_item_after_resource_name()
    {
        $resource = Fractal::create()
            ->withResourceName('tests')
            ->item($this->testBooks[0])
            ->transformWith(new TestTransformer);

        $this->assertEquals('tests', $resource->getResource()->getResourceKey());
    }

    /** @test */
    public function it_can_define_primitive_after_resource_name()
    {
        $resource = Fractal::create()
            ->withResourceName('tests')
            ->primitive($this->testBooks[0])
            ->transformWith(new TestTransformer);

        $this->assertEquals('tests', $resource->getResource()->getResourceKey());
    }
}
