<?php


namespace common\repositories\tag;

/**
 * Interface TagRepositoryInterface
 * @package common\repositories\tag
 */
interface TagRepositoryInterface
{
    /**
     * Returns all not hidden tags
     *
     * @return array
     */
    public function allActiveTag(): array;

    /**
     * Returns all tags mapped by name
     *
     * @return array
     */
    public function allTagsMappedByName(): array;
}