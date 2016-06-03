<?php


namespace common\components\rbac;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class Helpers
 * Rbac helpers
 *
 * @package common\components\rbac
 */
trait Helpers
{
    /**
     * @param string $permission
     * @param array $params
     * @param bool $allowCaching
     * @throws ForbiddenHttpException
     */
    protected function can(string $permission, array $params = [], $allowCaching = true)
    {
        if (!Yii::$app->user->can($permission, $params, $allowCaching)) {
            $exceptionMessage = $params['exceptionMessage'] ?? 'You are not allowed to perform this action.';
            throw new ForbiddenHttpException($exceptionMessage);
        }
    }
}