<?php

namespace Spatie\Fractalistic\Test\TestClasses;

class NullableTransformer extends TestTransformer
{
    /**
     * @param array $book
     *
     * @return array
     */
    public function transform(array $book = null)
    {
        return is_null($book) ? [] : parent::transform($book);
    }
}
