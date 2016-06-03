<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
                'attribute' => 'status',
                'filter' => \common\models\User::namedStatuses(),
                'content' => function (\common\models\User $data) {
                    return $data->getNamedStatus();
                }
            ],
            [
                'attribute' => 'role',
                'filter' => \common\models\User::namedRoles(),
                'content' => function (\common\models\User $data) {
                    return $data->getNamedRole();
                }
            ],
            'createdAt',
            'updatedAt',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {ban}',
                'buttons' => [
                    'ban' => function ($url, \common\models\User $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-ban-circle"></span>',
                            $url,
                            [
                                'title' => 'Ban',
                                'aria-label' => 'Ban',
                                'data-confirm' => 'Are you sure you want to ban this user?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
