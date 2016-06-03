<?php

namespace common\models\post;

use common\models\tag\PostTagAssn;
use creocoder\taggable\TaggableBehavior;
use Yii;
use common\models\tag\Tag;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property integer $hide
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PostTagAssn[] $postTagAssns
 * @property Tag[] $tags
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'taggable' => [
                'class' => TaggableBehavior::className(),
                // 'tagValuesAsArray' => false,
                // 'tagRelation' => 'tags',
                // 'tagValueAttribute' => 'name',
                // 'tagFrequencyAttribute' => 'frequency',
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'slug', 'text', 'hide', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'hide', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['slug'], 'string', 'max' => 250],
            ['tagValues', 'safe'],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'text' => 'Text',
            'hide' => 'Hide',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTagAssns()
    {
        return $this->hasMany(PostTagAssn::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('post_tag_assn', ['post_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return Query the active query used by this AR class.
     */
    public static function find()
    {
        return new Query(get_called_class());
    }
}
