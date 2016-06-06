<?php

namespace backend\controllers;

use common\models\tag\Tag;
use common\models\User;
use common\repositories\post\PostRepository;
use common\repositories\tag\TagRepository;
use Yii;
use common\models\post\Post;
use common\models\post\PostSearch;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\rbac\Helpers as RbacHelper;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    use RbacHelper;

    /**
     * @var TagRepository
     */
    private $tagRepository;
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * PostController constructor.
     * @param string $id
     * @param Module $module
     * @param TagRepository $tagRepository
     * @param PostRepository $postRepository
     * @param array $config
     */
    public function __construct($id, Module $module, TagRepository $tagRepository, PostRepository $postRepository, array $config = [])
    {
        parent::__construct($id, $module, $config);
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
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['moderator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView(int $id)
    {
        return $this->render('view', [
            'model' => $this->postRepository->getPostById($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->can('createPost');

        $model = new Post();

        $model->load(Yii::$app->request->post());
        $model->user_id = Yii::$app->user->identity->getId();

        $tags = $this->tagRepository->allTagsMappedByName();

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'tags' => $tags,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate(int $id)
    {
        $this->can('updatePost');
        $model = $this->postRepository->getPostById($id, ['tags']);
        $tags = $this->tagRepository->allTagsMappedByName();
        $postTags = ArrayHelper::map($model->tags, 'name', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'tags' => $tags,
                'postTags' => $postTags,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(int $id)
    {
        $this->postRepository->getPostById($id)->delete();

        return $this->redirect(['index']);
    }
}
