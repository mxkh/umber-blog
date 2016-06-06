<?php


namespace backend\models;


use common\components\rbac\AssignRule;
use common\models\User;
use yii\base\Model;
use Yii;
use yii\web\ForbiddenHttpException;
use common\components\rbac\Helpers as RbacHelper;

/**
 * Class UserForm
 * @package backend\models
 */
class UserForm extends Model
{
    use RbacHelper;

    const SCENARIO_CREATE = 'create';
    /**
     * @var bool
     */
    protected $isNewRecord = true;
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $email;
    /**
     * @var int
     */
    public $role;
    /**
     * @var int
     */
    public $status;
    /**
     * @var AssignRule
     */
    protected $assignRule;
    /**
     * @var \yii\web\User|User
     */
    protected $authUser;

    /**
     * UserForm constructor.
     * @param AssignRule $rule
     * @param array $config
     */
    public function __construct(AssignRule $rule, array $config = [])
    {
        $this->assignRule = $rule;
        $this->authUser = Yii::$app->user;

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
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.', 'on' => self::SCENARIO_CREATE],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.', 'on' => self::SCENARIO_CREATE],

            ['password', 'required', 'on' => self::SCENARIO_CREATE],
            ['password', 'string', 'min' => 6],

            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_DELETED, User::STATUS_BANNED]],
            ['role', 'in', 'range' => [User::ROLE_AUTHOR, User::ROLE_BANNED, User::ROLE_ADMIN, User::ROLE_MODERATOR]],
        ];
    }

    /**
     * @return boolean
     */
    public function isIsNewRecord()
    {
        return $this->isNewRecord;
    }

    /**
     * @param $isNewRecord
     * @return UserForm
     */
    public function setIsNewRecord($isNewRecord): UserForm
    {
        $this->isNewRecord = $isNewRecord;

        return $this;
    }
}