<?php

namespace Spatie\Fractalistic\Test;

class ScopeTest extends TestCase
{
    /** @test */
    public function it_uses_an_identifier_for_the_scope()
    {
        $scope = $this->fractal
            ->collection($this->testBooks, new TestTransformer(), 'books')
            ->parseIncludes('characters')
            ->createData();

        $this->assertEquals('books', $scope->getIdentifier());
    }
}
