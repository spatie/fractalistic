<?php

namespace Spatie\Fractalistic\Test;

use ArrayIterator;
use IteratorAggregate;
use ReturnTypeWillChange;

class TraversableClass implements IteratorAggregate
{
    /** @var array */
    protected $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    #[ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
