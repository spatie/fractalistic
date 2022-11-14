<?php

use Spatie\Fractalistic\Test\TestClasses\TestTransformer;
use function PHPUnit\Framework\assertEquals;

it('uses an identifier for the scope', function () {
    $scope = $this->fractal
        ->collection($this->testBooks, new TestTransformer(), 'books')
        ->parseIncludes('characters')
        ->createData();

    assertEquals('books', $scope->getIdentifier());
});
