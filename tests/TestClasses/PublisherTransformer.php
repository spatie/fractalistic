<?php

namespace Spatie\Fractalistic\Test\TestClasses;

use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class PublisherTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'books',
    ];

    public function transform(Publisher $publisher): array
    {
        return [
            'name' => $publisher->name,
            'address' => $publisher->address,
        ];
    }

    public function includeBooks(Publisher $publisher): Collection
    {
        return $this->collection($publisher->books(), new BookTransformer());
    }
}
