<?php
namespace frontend\models;

use common\components\rbac\AssignRule;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @var AssignRule
     */
    protected $assignRule;

    /**
     * SignupForm constructor.
     * @param AssignRule $rule
     * @param array $config
     */
    public function __construct(AssignRule $rule, array $config = [])
    {
        $this->assignRule = $rule;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->role = User::ROLE_AUTHOR;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if (!$user->save()) {
            return null;
        }

        $this->assignRule->assign($user, 'author');

        return $user;
    }
}
