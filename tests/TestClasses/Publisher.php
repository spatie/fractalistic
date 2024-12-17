<?php

namespace Spatie\Fractalistic\Test\TestClasses;

class Publisher
{
    public string $name;
    public string $address;
    /** @var Book[] */
    public array $books = [];

    /**
     * @param string $name
     * @param string $address
     */
    public function __construct(
        string $name,
        string $address
    ) {
        $this->name = $name;
        $this->address = $address;
    }

    public function books(): array
    {
        return $this->books;
    }
}
