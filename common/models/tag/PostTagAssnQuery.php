<?php

namespace common\models\tag;

/**
 * This is the ActiveQuery class for [[PostTagAssn]].
 *
 * @see PostTagAssn
 */
class PostTagAssnQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PostTagAssn[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PostTagAssn|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
