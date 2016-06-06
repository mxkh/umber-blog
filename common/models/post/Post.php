<?php

namespace common\models\post;

use common\models\tag\PostTagAssn;
use common\models\User;
use creocoder\taggable\TaggableBehavior;
use Yii;
use common\models\tag\Tag;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\StringHelper;

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
 * @property User $user
 * @property PostTagAssn[] $postTagAssns
 * @property Tag[] $tags
 */
class Post extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_NEW = 1;
    const STATUS_ON_MODERATION = 10;

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
            [['user_id', 'title', 'text'], 'required'],
            [['user_id', 'hide', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['slug'], 'string', 'max' => 250],
            ['tagValues', 'safe'],
            ['status', 'default', 'value' => self::STATUS_ON_MODERATION],
            ['status', 'in', 'range' => [self::STATUS_DELETED, self::STATUS_NEW, self::STATUS_ON_MODERATION]],
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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

    /**
     * @return array
     */
    public static function namedStatuses(): array
    {
        return [
            self::STATUS_ON_MODERATION => 'on moderation',
            self::STATUS_DELETED => 'deleted',
            self::STATUS_NEW => 'new',
        ];
    }

    /**
     * Property transformation method
     * @see https://github.com/yiisoft/yii2/blob/master/docs/guide/db-active-record.md#data-transformation-
     *
     * @return bool|string
     */
    public function getCreatedAt()
    {
        return date('Y-m-d H:i:s', $this->created_at);
    }

    /**
     * Property transformation method
     * @see https://github.com/yiisoft/yii2/blob/master/docs/guide/db-active-record.md#data-transformation-
     *
     * @return bool|string
     */
    public function getCreatedAtRelative()
    {
        return Yii::$app->formatter->asRelativeTime($this->created_at);
    }

    /**
     * Property transformation method
     * @see https://github.com/yiisoft/yii2/blob/master/docs/guide/db-active-record.md#data-transformation-
     *
     * @return bool|string
     */
    public function getUpdatedAt()
    {
        return date('Y-m-d H:i:s', $this->updated_at);
    }

    /**
     * Property transformation method
     * @see https://github.com/yiisoft/yii2/blob/master/docs/guide/db-active-record.md#data-transformation-
     *
     * @return string
     */
    public function getNamedStatus():string
    {
        return self::namedStatuses()[$this->status];
    }

    /**
     * @return string
     */
    public function getTruncatedText(): string
    {
        return StringHelper::truncate($this->text, 300);
    }
}
