<?php


namespace common\components\rbac;


use common\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * Class UserRoleRule
 * @package common\components\rbac
 */
class UserRoleRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'userRole';

    /**
     * @var \yii\web\User|User
     */
    protected $user;

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        $this->user = Yii::$app->user;

        if (true === $this->user->isGuest) {
            return false;
        }

        $role = $this->user->identity->role;

        if ($item->name === 'admin') {
            return $role == User::ROLE_ADMIN;
        } elseif ($item->name === 'moderator') {
            return $role == User::ROLE_ADMIN || $role == User::ROLE_MODERATOR;
        } elseif ($item->name === 'author') {
            return $role == User::ROLE_ADMIN || $role == User::ROLE_MODERATOR || $role == User::ROLE_AUTHOR;
        }

        return false;
    }
}