<?php

/** @var $model \common\models\post\Post */

$this->title = \yii\helpers\Html::encode($model->title) . '.';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1><?= \yii\helpers\Html::encode($model->title) ?></h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="col-lg-12">
                    <p>
                        <?php
                        /**
                         * In real apps is wise to use "htmlpurifier" helper
                         * Before insert to db
                         * To avoid XSS attacks or harmful code
                         *
                         * @see http://www.yiiframework.com/doc-2.0/yii-helpers-htmlpurifier.html
                         */
                        echo nl2br(\yii\helpers\Html::encode($model->text))
                        ?>
                    </p>
                    <p>
                        <small>
                            <span class="glyphicon glyphicon-user"></span>
                            <b><?= $model->user->username ?></b>
                        </small>
                        <br>
                        <small>
                            <span class="glyphicon glyphicon-time"></span>
                            <?= $model->getCreatedAtRelative() ?>
                        </small>
                    </p>
                    <p>
                        <?php foreach ($model->tags as $tag) { ?>
                            <a href="<?= \yii\helpers\Url::to(['site/index', 'tag' => $tag->name]) ?>" class="label label-info"><?= $tag->name ?></a>
                        <?php } ?>
                    </p>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>


