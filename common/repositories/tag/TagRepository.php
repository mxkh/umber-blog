<?php


namespace common\repositories\tag;

use common\models\tag\Tag;
use yii\helpers\ArrayHelper;

/**
 * Class TagRepository
 * @package common\repositories\tag
 */
class TagRepository implements TagRepositoryInterface
{

    /**
     * Returns all tags mapped by name
     *
     * @return array
     */
    public function allTagsMappedByName(): array
    {
        $tags = $this->allActiveTag();

        return ArrayHelper::map($tags, 'name', 'name');
    }

    /**
     * Returns all not hidden tags
     *
     * @return array
     */
    public function allActiveTag(): array
    {
        return Tag::find()->notHidden()->all();
    }
}