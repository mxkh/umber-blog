<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\post\Post */
/* @var $tags common\models\tag\Tag[] */
/* @var $postTags common\models\tag\Tag[] */

$this->title = 'Update Post: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags,
        'postTags' => $postTags,
    ]) ?>

</div>
