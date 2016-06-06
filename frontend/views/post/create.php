<?php
/** @var $model \common\models\post\Post */
/* @var $tags \common\models\tag\Tag[] */

$this->title = 'Create post.';
?>

<div class="post-create">

    <div class="jumbotron">
        <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags,
    ]) ?>

</div>
