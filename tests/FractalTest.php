<?php

namespace Spatie\Fractalistic\Test;

use League\Fractal\Pagination\Cursor;
use Spatie\Fractalistic\ArraySerializer;

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
    public function it_can_provides_a_method_to_specify_the_transformer()
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
    public function it_provides_chainable_methods()
    {
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->item('test'));
        $this->assertInstanceOf(get_class($this->fractal), $this->fractal->collection([]));
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
}
