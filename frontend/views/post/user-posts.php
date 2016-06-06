<?php

/* @var $this yii\web\View */
/* @var $posts \common\models\post\Post[] */
/* @var $pagination \yii\data\Pagination */

$this->title = 'User posts.';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>My Posts.</h1>
    </div>

    <div class="body-content">

        <?php if (!count($posts)) { ?>
            <div class="jumbotron">
                <h2>Empty.</h2>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <?php foreach ($posts as $post) { ?>
                    <div class="col-lg-12">
                        <?php if ($post->status == \common\models\post\Post::STATUS_ON_MODERATION) { ?>
                            <span class="label label-warning">On moderation / hidden: <?= $post->hide ?></span>
                        <?php } else { ?>
                            <span class="label label-success">Published / hidden: <?= $post->hide ?></span>
                        <?php } ?>
                        <h2>
                            <a href="<?= \yii\helpers\Url::to(['post/user-post', 'slug' => $post->slug]) ?>">
                                <?= \yii\helpers\Html::encode($post->title) ?>
                            </a>
                        </h2>

                        <p>
                            <?php
                            /**
                             * In real apps is wise to use "htmlpurifier" helper
                             * Before insert to db
                             * To avoid XSS attacks or harmful code
                             *
                             * @see http://www.yiiframework.com/doc-2.0/yii-helpers-htmlpurifier.html
                             */
                            echo \yii\helpers\Html::encode($post->getTruncatedText())
                            ?>
                        </p>
                        <p>
                            <small>
                                <span class="glyphicon glyphicon-user"></span>
                                <b><?= $post->user->username ?></b>
                            </small>
                            <br>
                            <time>
                                <small>
                                    <span class="glyphicon glyphicon-time"></span>
                                    <?= $post->getCreatedAtRelative() ?>
                                </small>
                            </time>
                            <a href="<?= \yii\helpers\Url::to(['/post/edit', 'slug' => $post->slug]) ?>" class="btn btn-link">
                                Edit
                            </a>
                        </p>
                        <hr>
                    </div>
                <?php } ?>
                <p>
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $pagination,
                    ]);
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>