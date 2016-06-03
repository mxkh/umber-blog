<?php


namespace console\controllers;

use common\components\rbac\UserPostOwnerRule;
use common\models\User;
use Yii;
use yii\console\Controller;
use common\components\rbac\UserRoleRule;
use yii\rbac\ManagerInterface;

/**
 * Class RbacController
 * Using for building authorization data
 *
 * @package console\controllers
 */
class RbacController extends Controller
{
    /**
     * Init console method
     * Run `php yii rbac/init`
     * @return int
     */
    public function actionInit(): int
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        /*
         * Create roles
         */
        $guest = $auth->createRole('guest');
        $author = $auth->createRole('author');
        $moderator = $auth->createRole('moderator');
        $admin = $auth->createRole('admin');
        $banned = $auth->createRole('banned');

        /*
         * Add rule
         */
        $userRoleRule = new UserRoleRule;
        $auth->add($userRoleRule);

        $userPostOwnerRule = new UserPostOwnerRule;
        $auth->add($userPostOwnerRule);

        /*
         * Create permissions
         */

        // Post
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create post';
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $userPostOwnerRule->name;
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';

        // User
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create user';
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update user';
        $banUser = $auth->createPermission('banUser');
        $banUser->description = 'Ban user';

        // Category
        $createCategory = $auth->createPermission('createCategory');
        $createCategory->description = 'Create category';
        $updateCategory = $auth->createPermission('updateCategory');
        $updateCategory->description = 'Update category';

        /*
         * Add permissions
         */
        $auth->add($createPost);
        $auth->add($updateOwnPost);
        $auth->add($updatePost);
        $auth->add($createUser);
        $auth->add($updateUser);
        $auth->add($banUser);
        $auth->add($createCategory);
        $auth->add($updateCategory);

        /*
         * Add rule name
         */
        $guest->ruleName = $userRoleRule->name;
        $author->ruleName = $userRoleRule->name;
        $banned->ruleName = $userRoleRule->name;
        $moderator->ruleName = $userRoleRule->name;
        $admin->ruleName = $userRoleRule->name;

        /*
         * Add roles
         */
        $auth->add($guest);
        $auth->add($author);
        $auth->add($banned);
        $auth->add($moderator);
        $auth->add($admin);

        /*
         * Add permission per role
         */

        // Author
        $auth->addChild($author, $createPost);
        $auth->addChild($author, $updateOwnPost);

        // Moderator
        $auth->addChild($moderator, $updatePost);
        $auth->addChild($moderator, $createCategory);
        $auth->addChild($moderator, $updateCategory);
        $auth->addChild($moderator, $author);

        // Admin
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $banUser);
        $auth->addChild($admin, $moderator);


        $this->createAdmin($auth);

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @param ManagerInterface $auth
     */
    protected function createAdmin(ManagerInterface $auth)
    {
        $admin = User::find()->where('`role` = :admin', [':admin' => User::ROLE_ADMIN])->one();

        if (!$admin) {
            $user = new User();
            $user->username = 'admin';
            $user->email = 'admin@example.com';
            $user->role = User::ROLE_ADMIN;
            $user->setPassword('qawsedrf');
            $user->generateAuthKey();
            $user->save(false);

            $authorRole = $auth->getRole('admin');
            $auth->assign($authorRole, $user->getId());
        }
    }
}