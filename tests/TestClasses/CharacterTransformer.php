<?php

namespace Spatie\Fractalistic\Test\TestClasses;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

final class CharacterTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'book',
        'author',
    ];

    public function transform(Character $character): array
    {
        $parentScope = last($this->getCurrentScope()->getParentScopes());
        $data = [
            'name' => $character->name,
            'current_scope' => $this->getCurrentScope()->getScopeIdentifier(),
            'parent_scope' => $parentScope,
            'scope_identifier' => $this->getCurrentScope()->getIdentifier(),
        ];

        if ($parentScope === 'author') {
            $data['called_by_author'] = 'indeed!';
        }

        if ($parentScope === 'books') {
            $data['called_by_book'] = 'yes!';
        }

        return $data;
    }

    public function includeBook(Character $character): Item
    {
        return $this->item($character->book(), new BookTransformer());
    }
}
