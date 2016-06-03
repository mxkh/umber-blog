<?php


namespace common\repositories\user;

use backend\models\UserForm;
use common\models\User;

/**
 * Interface UserRepositoryInterface
 * @package common\repositories\user
 */
interface UserRepositoryInterface
{
    /**
     * @param UserForm $form
     * @return User
     */
    public function create(UserForm $form): User;

    /**
     * @param UserForm $form
     * @param User $user
     * @return User
     */
    public function update(UserForm $form, User $user): User;

    /**
     * @param User $user
     * @return void
     */
    public function delete(User $user);

    /**
     * @param User $user
     * @return void
     */
    public function ban(User $user);
}