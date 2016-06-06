<?php
namespace frontend\controllers;

use common\models\post\Post;
use common\models\tag\Query;
use common\repositories\post\PostRepository;
use common\repositories\tag\TagRepository;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Module;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @var SignupForm
     */
    protected $signUpForm;
    /**
     * @var LoginForm
     */
    protected $loginForm;
    /**
     * @var ContactForm
     */
    protected $contactForm;
    /**
     * @var TagRepository
     */
    private $tagRepository;
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * SiteController constructor.
     * @param string $id
     * @param Module $module
     * @param ContactForm $contactForm
     * @param LoginForm $loginForm
     * @param SignupForm $signUpForm
     * @param TagRepository $tagRepository
     * @param PostRepository $postRepository
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        ContactForm $contactForm,
        LoginForm $loginForm,
        SignupForm $signUpForm,
        TagRepository $tagRepository,
        PostRepository $postRepository,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->signUpForm = $signUpForm;
        $this->contactForm = $contactForm;
        $this->loginForm = $loginForm;
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @param string $tag
     * @return mixed
     */
    public function actionIndex($tag = '')
    {
        $data = $this->postRepository->getPostFilteredByTag($tag, ['user'], 10);
        $tags = $this->tagRepository->allActiveTag();

        return $this->render('index', [
            'posts' => $data['posts'],
            'pagination' => $data['pagination'],
            'tags' => $tags,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if ($this->loginForm->load(Yii::$app->request->post()) && $this->loginForm->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $this->loginForm,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        if ($this->contactForm->load(Yii::$app->request->post()) && $this->contactForm->validate()) {

            if ($this->contactForm->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $this->contactForm,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if ($this->signUpForm->load(Yii::$app->request->post())) {
            if ($user = $this->signUpForm->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $this->signUpForm,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
