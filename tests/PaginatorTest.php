<?php

use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\JsonApiSerializer;
use Spatie\Fractalistic\Test\TestClasses\TestTransformer;
use function PHPUnit\Framework\assertEquals;

it('generates paginated data', function () {
    $books = [$this->testBooks[0]];
    $array = $this->fractal
        ->collection($books, new TestTransformer())
        ->serializeWith(new JsonApiSerializer())
        ->paginateWith(new IlluminatePaginatorAdapter(
        new LengthAwarePaginator($books, 2, 1)
        ))
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
        ],
        'meta' => [
        'pagination' => [
            'total' => 2,
            'count' => 1,
            'per_page' => 1,
            'current_page' => 1,
            'total_pages' => 2,
        ],
        ],
        'links' => [
        'self' => '/?page=1',
        'first' => '/?page=1',
        'next' => '/?page=2',
        'last' => '/?page=2',
        ],
    ];

    assertEquals($expectedArray, $array);
});
