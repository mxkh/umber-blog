<?php

/* @var $this yii\web\View */
/* @var \common\models\User $user */

$this->title = 'My Yii Application';
$user = Yii::$app->user->getIdentity();
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hello, <?= $user->username ?>!</h1>

        <p class="lead">This is admin dashboard.</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2><span class="glyphicon glyphicon-file"></span> Posts</h2>

                <p>In this section you can manage all posts.</p>

                <p>
                    <a class="btn btn-default" href="<?= \yii\helpers\Url::to(['/post']) ?>">Manage posts &raquo;</a>
                </p>
            </div>
                <div class="col-lg-4">
                    <h2><span class="glyphicon glyphicon-user"></span> Users</h2>

                    <p>In this section you can manage all users.</p>

                    <p>
                        <a class="btn btn-default" href="<?= \yii\helpers\Url::to(['/user']) ?>">Manage users &raquo;</a>
                    </p>
                </div>
            <div class="col-lg-4">
                <h2><span class="glyphicon glyphicon-tags"></span> Categories</h2>

                <p>In this section you can manage all categories.</p>

                <p>
                    <a class="btn btn-default" href="<?= \yii\helpers\Url::to(['/tag']) ?>">Manage categories &raquo;</a>
                </p>
            </div>
        </div>

    </div>
</div>
