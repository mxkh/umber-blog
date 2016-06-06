<?php


namespace common\components\rbac;

use Yii;
use common\models\User;
use yii\rbac\Rule;

/**
 * Class UserPostOwnerRule
 * @package common\components\rbac
 */
class UserPostOwnerRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'isPostOwner';

    /**
     * @var \yii\web\User|User
     */
    protected $user;

    /**
     * @param $user
     * @param $item
     * @param $params
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        $this->user = Yii::$app->user;
        $role = $this->user->identity->role;

        if ($role === 'admin') {
            return true;
        }

        return isset($params['ownerId']) ? $this->user->getId() == $params['ownerId'] : false;
    }
}