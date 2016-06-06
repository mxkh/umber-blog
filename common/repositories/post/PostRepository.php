<?php


namespace common\repositories\post;

use common\models\post\Post;
use common\models\tag\Query;
use yii\data\Pagination;
use common\components\rbac\Helpers as RbacHelper;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\Request;

/**
 * Class PostRepository
 * @package common\repositories\post
 */
class PostRepository implements PostRepositoryInterface
{
    use RbacHelper;

    /**
     * Returns single post selected by slug param
     *
     * @param string $slug
     * @return Post
     * @throws NotFoundHttpException
     */
    public function getPostBySlug(string $slug): Post
    {
        $model = Post::find()
            ->slug($slug)
            ->active()
            ->notHidden()
            ->with('user')
            ->one();

        if (null === $model) {
            throw new NotFoundHttpException('The requested page does not exist.');

        }

        return $model;
    }


    /**
     * Returns single post which selected by slug and visible only for owner(author)
     *
     * @param string $slug
     * @return Post
     * @throws NotFoundHttpException
     */
    public function getOwnerPostBySlug(string $slug): Post
    {
        $model = Post::find()
            ->slug($slug)
            ->notDeleted()
            ->with('user')
            ->one();

        if (null === $model) {
            throw new NotFoundHttpException('The requested page does not exist.');

        }

        return $model;
    }

    /**
     * Returns query of posts where user is owner by id
     *
     * @param int $id
     * @param array $with
     * @param int $limit
     * @return array
     */
    public function getOwnerPostsByPage(int $id, array $with = [], int $limit = 10): array
    {
        $query = Post::find()
            ->owner($id)
            ->notDeleted()
            ->with($with);

        $count = $query->count();
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $limit,
        ]);

        /** @var Post[] $posts */
        $posts = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'posts' => $posts,
            'pagination' => $pagination,
        ];
    }

    /**
     * Returns single post
     *
     * @param int $id
     * @param array $with
     * @return Post
     * @throws NotFoundHttpException
     */
    public function getPostById(int $id, array $with = []): Post
    {
        $model = Post::find()->where('id=:id', [':id' => $id])->with($with)->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Returns posts filtered by tag
     *
     * @param string $tag
     * @param array $with
     * @param int $limit
     * @return array
     */
    public function getPostFilteredByTag(string $tag, array $with = [], int $limit = 10): array
    {
        $query = Post::find()
            ->active()
            ->notHidden()
            ->with($with);

        if (!empty($tag)) {
            $query->joinWith([
                'tags t' => function (Query $query) use ($tag) {
                    $query->andFilterWhere(['t.name' => $tag]);
                }
            ]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $limit]);

        /** @var Post[] $posts */
        $posts = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'posts' => $posts,
            'pagination' => $pagination,
        ];
    }
}