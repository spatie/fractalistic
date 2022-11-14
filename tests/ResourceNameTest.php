<?php

use League\Fractal\Serializer\JsonApiSerializer;
use Spatie\Fractalistic\Test\TestClasses\TestTransformer;
use function PHPUnit\Framework\assertEquals;

it('uses a custom resource name when creating a collection', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer(), 'books')
        ->serializeWith(new JsonApiSerializer())
        ->toArray();

    $expectedArray = [
        'data' => [
        [
            'id' => 1,
            'type' => 'books',
            'attributes' => [
            'author' => 'Philip K Dick',
            ],
        ],
        [
            'id' => 2,
            'type' => 'books',
            'attributes' => [
            'author' => 'George R. R. Satan',
            ],
        ],
        ],
    ];

    assertEquals($expectedArray, $array);
});

it('uses a custom resource name when using setter', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->withResourceName('books')
        ->serializeWith(new JsonApiSerializer())
        ->toArray();

    $expectedArray = [
        'data' => [
        [
            'id' => 1,
            'type' => 'books',
            'attributes' => [
            'author' => 'Philip K Dick',
            ],
        ],
        [
            'id' => 2,
            'type' => 'books',
            'attributes' => [
            'author' => 'George R. R. Satan',
            ],
        ],
        ],
    ];

    assertEquals($expectedArray, $array);
});

it('uses a custom resource name for an item', function () {
    $array = $this->fractal
        ->item($this->testBooks[0], new TestTransformer())
        ->withResourceName('book')
        ->serializeWith(new JsonApiSerializer())
        ->toArray();

    $expectedArray = [
        'data' => [
        'id' => 1,
        'type' => 'book',
        'attributes' => [
            'author' => 'Philip K Dick',
        ],
        ],
    ];

    assertEquals($expectedArray, $array);
});

it('uses null as resource name when not set', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->serializeWith(new JsonApiSerializer())
        ->toArray();

    $expectedArray = [
        'data' => [
        [
            'id' => 1,
            'type' => null,
            'attributes' => [
            'author' => 'Philip K Dick',
            ],
        ],
        [
            'id' => 2,
            'type' => null,
            'attributes' => [
            'author' => 'George R. R. Satan',
            ],
        ],
        ],
    ];

    assertEquals($expectedArray, $array);
});
