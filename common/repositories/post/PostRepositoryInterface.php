<?php


namespace common\repositories\post;


use common\models\post\Post;

/**
 * Interface PostRepositoryInterface
 * @package common\repositories\post
 */
interface PostRepositoryInterface
{
    /**
     * Returns single post which selected by slug
     *
     * @param string $slug
     * @return Post
     */
    public function getPostBySlug(string $slug): Post;

    /**
     * Returns array of posts where user is owner by id
     *
     * @param int $id
     * @param array $with
     * @param int $limit
     * @return array
     */
    public function getOwnerPostsByPage(int $id, array $with = [], int $limit = 10): array;

    /**
     * Returns single post which selected by slug and visible only for author
     *
     * @param string $slug
     * @return Post
     */
    public function getOwnerPostBySlug(string $slug): Post;

    /**
     * Returns single post
     *
     * @param int $id
     * @param array $with
     * @return Post
     */
    public function getPostById(int $id, array $with = []): Post;

    /**
     * Returns posts filtered by tag
     *
     * @param string $tag
     * @param array $with
     * @param int $limit
     * @return array
     */
    public function getPostFilteredByTag(string $tag, array $with = [], int $limit = 10): array;
}