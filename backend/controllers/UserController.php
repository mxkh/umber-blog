<?php

namespace backend\controllers;

use backend\models\UserForm;
use common\repositories\user\UserRepository;
use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @var UserForm
     */
    private $userForm;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param string $id
     * @param Module $module
     * @param UserForm $userForm
     * @param UserRepository $userRepository
     * @param array $config
     */
    public function __construct($id, Module $module, UserForm $userForm, UserRepository $userRepository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userForm = $userForm;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['moderator'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'ban' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->userForm->setScenario(UserForm::SCENARIO_CREATE);

        if ($this->userForm->load(Yii::$app->request->post()) && $this->userForm->validate()) {
            $model = $this->userRepository->create($this->userForm);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $this->userForm,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var User $model */
        $model = $this->findModel($id);

        // Load form from model
        $this->userForm
            ->setIsNewRecord(false)
            ->setAttributes($model->getAttributes());

        if ($this->userForm->load(Yii::$app->request->post()) && $this->userForm->validate()) {
            $model = $this->userRepository->update($this->userForm, $model);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'form' => $this->userForm,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /** @var User $model */
        $model = $this->findModel($id);

        $this->userRepository->delete($model);

        return $this->redirect(['index']);
    }

    /**
     * Ban an existing User.
     * If ban is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBan($id)
    {
        /** @var User $model */
        $model = $this->findModel($id);

        $this->userRepository->ban($model);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
