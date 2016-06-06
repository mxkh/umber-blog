<?php

namespace common\models\tag;

/**
 * This is the ActiveQuery class for [[Tag]].
 *
 * @see Tag
 */
class Query extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function notHidden()
    {
        return $this->andWhere(Tag::tableName() . '.hide=0');
    }

    /**
     * @inheritdoc
     * @return Tag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
