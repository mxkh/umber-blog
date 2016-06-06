<?php


namespace frontend\controllers;

use common\models\post\Post;
use common\repositories\post\PostRepository;
use common\repositories\tag\TagRepository;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\components\rbac\Helpers as RbacHelper;
use Yii;

/**
 * Class PostController
 * @package frontend\controllers
 */
class PostController extends Controller
{
    use RbacHelper;

    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var TagRepository
     */
    private $tagRepository;

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
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
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
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['author'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single Post model.
     * @param string $slug
     * @return mixed
     */
    public function actionView(string $slug)
    {
        return $this->render('view', [
            'model' => $this->postRepository->getPostBySlug($slug),
        ]);
    }


    /**
     * Displays a user own posts
     * @return string
     */
    public function actionUserPosts()
    {
        $data = $this->postRepository->getOwnerPostsByPage(Yii::$app->user->identity->getId(), ['user']);

        return $this->render('user-posts', $data);
    }

    /**
     * Displays a user own post
     *
     * @param string $slug
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUserPost(string $slug)
    {
        return $this->render('view', [
            'model' => $this->postRepository->getOwnerPostBySlug($slug),
        ]);
    }

    public function actionCreate()
    {
        $this->can('createPost');

        $model = new Post;

        $model->load(Yii::$app->request->post());
        $model->user_id = Yii::$app->user->identity->getId();

        $tags = $this->tagRepository->allTagsMappedByName();

        if ($model->save()) {
            return $this->redirect(['/post/user-posts', 'slug' => $model->slug]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'tags' => $tags,
            ]);
        }
    }

    public function actionEdit(string $slug)
    {
        $this->can('updateOwnPost', ['ownerId' => Yii::$app->user->identity->getId()]);

        /** @var Post $model */
        $model = $this->postRepository->getOwnerPostBySlug($slug);
        $model->loadDefaultValues(false);

        $tags = $this->tagRepository->allTagsMappedByName();
        $postTags = ArrayHelper::map($model->tags, 'name', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/post/user-post', 'slug' => $model->slug]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'tags' => $tags,
                'postTags' => $postTags,
            ]);
        }
    }
}