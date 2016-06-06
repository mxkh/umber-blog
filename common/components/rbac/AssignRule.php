<?php


namespace common\components\rbac;


use common\models\User;
use yii\rbac\Assignment;
use yii\rbac\ManagerInterface;

/**
 * Class AssignRule
 * @package common\components\rbac
 */
class AssignRule
{
    /**
     * @var ManagerInterface
     */
    protected $auth;

    /**
     * AssignRule constructor.
     */
    public function __construct()
    {
        $this->auth = \Yii::$app->authManager;
    }

    /**
     * @param User $user
     * @param string $role
     * @return AssignRule
     */
    public function assign(User $user, string $role): AssignRule
    {
        $authorRole = $this->auth->getRole($role);
        $this->auth->assign($authorRole, $user->getId());

        return $this;
    }

    /**
     * @param User $user
     * @param string $role
     * @return AssignRule
     */
    public function revoke(User $user, string $role):AssignRule
    {
        $authorRole = $this->auth->getRole($role);
        $this->auth->revoke($authorRole, $user->getId());

        return $this;
    }
}