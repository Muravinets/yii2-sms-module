<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ihacklog\sms\models\Sms */

$this->title = 'Update Sms: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$boxButtons = ['{cancel}'];

/*
if (Yii::$app->user->can('BCreateBlogs')) {
$boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteBlogs')) {
$boxButtons[] = '{delete}';
}
*/
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>
<div class="row">
    <div class="col-sm-12">
        <?php
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'box' => $box,
                'templateArr' => $templateArr,
                'channelTypeArr' => $channelTypeArr,
            ]
        );
?>
    </div>
</div>
