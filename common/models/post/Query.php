<?php

namespace common\models\post;

use creocoder\taggable\TaggableQueryBehavior;

/**
 * This is the ActiveQuery class for [[Post]].
 *
 * @see Post
 */
class Query extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(Post::tableName() . '.status=:status', [':status' => Post::STATUS_NEW]);
    }

    /**
     * @return $this
     */
    public function notHidden()
    {
        return $this->andWhere(Post::tableName() . '.hide=:hide', [':hide' => 0]);
    }

    public function notDeleted()
    {
        return $this->andWhere(Post::tableName() . '.status>:status', [':status' => Post::STATUS_DELETED]);
    }

    /**
     * @return $this
     */
    public function hidden()
    {
        return $this->andWhere(Post::tableName() . '.hide=:hide', [':hide' => 1]);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function owner(int $id)
    {
        return $this->andWhere(Post::tableName() . '.user_id=:id', [':id' => $id]);
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function slug(string $slug)
    {
        return $this->andWhere(Post::tableName() . '.slug=:slug', [':slug' => $slug]);
    }

    /**
     * @inheritdoc
     * @return Post[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Post|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
