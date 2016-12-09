<?php

namespace Spatie\Fractalistic\Test;

use ArrayIterator;
use IteratorAggregate;

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
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
