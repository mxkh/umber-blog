<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\UserForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\User::namedStatuses()) ?>

    <?= $form->field($model, 'role')->dropDownList(\common\models\User::namedRoles()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isIsNewRecord() ? 'Create' : 'Update', ['class' => $model->isIsNewRecord() ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
