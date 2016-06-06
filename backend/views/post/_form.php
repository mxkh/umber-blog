<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\post\Post */
/* @var $form yii\widgets\ActiveForm */
/* @var $tags \common\models\tag\Tag[] */
/* @var $postTags common\models\tag\Tag[] */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <p>
        <?= Select2::widget([
            'model' => $model,
            'name' => 'Post[tagValues]',
            'value' => $postTags??[],
            'data' => $tags,
            'options' => ['placeholder' => 'Select a category ...', 'multiple' => true],
            'pluginOptions' => [
                'tags' => true,
                'maximumInputLength' => 20
            ],
        ]); ?>
    </p>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'hide')->checkbox() ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\post\Post::namedStatuses()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
