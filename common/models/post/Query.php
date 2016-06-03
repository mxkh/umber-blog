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

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
