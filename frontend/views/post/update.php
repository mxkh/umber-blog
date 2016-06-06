<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\post\Post */
/* @var $tags common\models\tag\Tag[] */
/* @var $postTags common\models\tag\Tag[] */

$this->title = Html::encode($model->title) . '.';
?>
<div class="post-update">

    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags,
        'postTags' => $postTags,
    ]) ?>

</div>
