<?php

/* @var $this yii\web\View */
/* @var $posts \common\models\post\Post[] */
/* @var $pagination \yii\data\Pagination */
/* @var $tags \common\models\tag\Tag[] */

$this->title = 'Umber Blog';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>The Umber Blog.</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-10">
                <?php if (!count($posts)) { ?>
                    <div class="jumbotron">
                        <h2>Empty.</h2>
                    </div>
                <?php } ?>
                <?php foreach ($posts as $post) { ?>
                    <div class="col-lg-12">
                        <h2>
                            <a href="<?= \yii\helpers\Url::to(['post/view', 'slug' => $post->slug]) ?>">
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
                        </p>
                        <hr>
                    </div>
                <?php } ?>
                <p>
                    <?php
                    echo \yii\widgets\LinkPager::widget([
                        'pagination' => $pagination,
                    ]);
                    ?>
                </p>
            </div>
            <div class="col-lg-2">
                <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="btn btn-default">Reset filter</a>
                <hr>
                <?php foreach ($tags as $tag) { ?>
                    <a href="<?= \yii\helpers\Url::to(['site/index', 'tag' => $tag->name]) ?>" class="label label-info"><?= $tag->name ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
