<?php

use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;
use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Fractal;
use Spatie\Fractalistic\Test\NullableTransformer;
use Spatie\Fractalistic\Test\TestTransformer;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

it('can transform multiple items using a transformer to json', function () {
    $json = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->toJson();

    $expectedJson = '{"data":[{"id":1,"author":"Philip K Dick"},{"id":2,"author":"George R. R. Satan"}]}';

    assertEquals($expectedJson, $json);
});

it('can accept a bitmask when converting to json', function () {
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

    assertEquals($expectedJson, $json);
});

it('can transform multiple items using a transformer to an array', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ]];

    assertEquals($expectedArray, $array);
});

it('can transform a collection using a callback', function () {
    $array = $this->fractal
        ->collection($this->testBooks, function ($book) {
        return ['id' => $book['id']];
        })->toArray();

    $expectedArray = ['data' => [
        ['id' => 1],
        ['id' => 2],
    ]];

    assertEquals($expectedArray, $array);
});

it('can transform a collection using a class name', function () {
    $json = $this->fractal
        ->collection($this->testBooks, TestTransformer::class)
        ->toJson();

    $expectedJson = '{"data":[{"id":1,"author":"Philip K Dick"},{"id":2,"author":"George R. R. Satan"}]}';

    assertEquals($expectedJson, $json);
});

it('provides a method to specify the transformer', function () {
    $array = $this->fractal
        ->collection($this->testBooks)
        ->transformWith(new TestTransformer())
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ]];

    assertEquals($expectedArray, $array);
});

it('can accept a class name as a transformer', function () {
    $array = $this->fractal
        ->collection($this->testBooks)
        ->transformWith(TestTransformer::class)
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ]];

    assertEquals($expectedArray, $array);
});

it('can perform a single item', function () {
    $array = $this->fractal
        ->item($this->testBooks[0], new TestTransformer())
        ->toArray();

    $expectedArray = ['data' => [
        'id' => 1, 'author' => 'Philip K Dick', ]];

    assertEquals($expectedArray, $array);
});

it('can create a resource', function () {
    $resource = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->getResource();

    assertInstanceOf(ResourceInterface::class, $resource);
});

it('can create fractal data', function () {
    $resource = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->createData();

    assertInstanceOf(\League\Fractal\Scope::class, $resource);
});

it('can perform a null item', function () {
    $array = Fractal::create(null, NullableTransformer::class)
        ->serializeWith(new JsonApiSerializer())
        ->withResourceName('books')
        ->toArray();

    $expectedArray = ['data' => null];

    assertEquals($expectedArray, $array);
});

it('can create a null resource', function () {
    $resource = Fractal::create(null, NullableTransformer::class)
        ->serializeWith(new JsonApiSerializer())
        ->withResourceName('books')
        ->getResource();

    assertInstanceOf(ResourceInterface::class, $resource);
});

it('can create null fractal data', function () {
    $resource = Fractal::create(null, NullableTransformer::class)
        ->serializeWith(new JsonApiSerializer())
        ->withResourceName('books')
        ->createData();

    assertInstanceOf(\League\Fractal\Scope::class, $resource);
});

it('provides chainable methods', function () {
    assertInstanceOf(get_class($this->fractal), $this->fractal->item('test'));
    assertInstanceOf(get_class($this->fractal), $this->fractal->collection([]));
    assertInstanceOf(get_class($this->fractal), $this->fractal->primitive(123));
    assertInstanceOf(get_class($this->fractal), $this->fractal->transformWith(function () {
    }));
    assertInstanceOf(get_class($this->fractal), $this->fractal->serializeWith(new ArraySerializer()));
    assertInstanceOf(get_class($this->fractal), $this->fractal->withCursor(
        new Cursor(0, null, 10, 10)
    ));
    assertInstanceOf(get_class($this->fractal), $this->fractal->addMeta([]));
    assertInstanceOf(get_class($this->fractal), $this->fractal->paginateWith(
        $this->createMock('League\Fractal\Pagination\PaginatorInterface')
    ));
});

it('can perform an empty array', function () {
    $array = Fractal::create([], TestTransformer::class)
        ->toArray();

    $expectedArray = ['data' => []];

    assertEquals($expectedArray, $array);
});

it('can define collection after resource name', function () {
    $resource = Fractal::create()
        ->withResourceName('tests')
        ->collection($this->testBooks)
        ->transformWith(new TestTransformer());

    assertEquals('tests', $resource->getResource()->getResourceKey());
});

it('can define item after resource name', function () {
    $resource = Fractal::create()
        ->withResourceName('tests')
        ->item($this->testBooks[0])
        ->transformWith(new TestTransformer());

    assertEquals('tests', $resource->getResource()->getResourceKey());
});

it('can define primitive after resource name', function () {
    $resource = Fractal::create()
        ->withResourceName('tests')
        ->primitive($this->testBooks[0])
        ->transformWith(new TestTransformer());

    assertEquals('tests', $resource->getResource()->getResourceKey());
});

it('can convert into something that is json serializable', function () {
    $jsonSerialized = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->jsonSerialize();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ]];

    assertEquals($expectedArray, $jsonSerialized);
});
