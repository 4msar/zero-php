<?php

namespace App\Core\Traits;

use App\Core\Collection;

/**
 * Use Object Data
 */
trait UseObject
{
    /**
     * Generate Object of single item
     * @param  array $item
     * @return object
     */
    public function parseItem($item): Collection
    {
        return new Collection($item);
    }

    /**
     * Generate Object of single data
     * @param  array $data
     * @return object
     */
    public function parseItems($data): Collection
    {
        return new Collection($data, 'collection');
    }
}
