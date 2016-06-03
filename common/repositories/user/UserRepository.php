<?php


namespace common\repositories\user;


use backend\models\UserForm;
use common\components\rbac\AssignRule;
use common\models\User;
use common\components\rbac\Helpers as RbacHelper;

class UserRepository implements UserRepositoryInterface
{
    use RbacHelper;
    /**
     * @var AssignRule
     */
    private $assignRule;
    /**
     * @var User
     */
    private $user;

    /**
     * UserRepository constructor.
     * @param AssignRule $assignRule
     * @param User $user
     */
    public function __construct(AssignRule $assignRule, User $user)
    {
        $this->assignRule = $assignRule;
        $this->user = $user;
    }

    /**
     * @param UserForm $form
     * @return User
     */
    public function create(UserForm $form): User
    {
        $this->can('createUser'); // Check permission before create

        $this->user->setAttributes($form->getAttributes(null, ['password']));
        $this->user->setPassword($form->password);
        $this->user->generateAuthKey();
        $this->user->save(false);

        $this->assignRule->assign($this->user, User::namedRoles()[$form->role]);

        return $this->user;
    }

    /**
     * @param UserForm $form
     * @param User $user
     * @return User
     */
    public function update(UserForm $form, User $user): User
    {
        $this->can('updateUser'); // Check permission before update

        $role = $user->getOldAttribute('role');
        $form->role = $form->status == User::STATUS_BANNED ? User::ROLE_BANNED : $form->role;

        $user->setAttributes($form->getAttributes(null, ['password']));
        $user->setPassword($form->password);
        $user->generateAuthKey();
        $user->update(false);

        $this->assignRule
            ->revoke($user, User::namedRoles()[$role])
            ->assign($user, User::namedRoles()[$form->role]);

        return $user;
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $user->delete();

        $this->assignRule->revoke($user, User::namedRoles()[$user->role]);
    }

    /**
     * @param User $user
     */
    public function ban(User $user)
    {
        $this->assignRule
            ->revoke($user, User::namedRoles()[$user->role])
            ->assign($user, User::namedRoles()[User::ROLE_BANNED]);

        $user->status = User::STATUS_BANNED;
        $user->role = User::ROLE_BANNED;
        $user->update(false);
    }
}