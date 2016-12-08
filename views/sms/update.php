<?php

use yii\helpers\Html;
use vova07\themes\admin\widgets\Box;

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
        <?php  $box = Box::begin(
            [
                'title' => $this->title,
                'renderBody' => false,
                'options' => [
                    'class' => 'box-success'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'box' => $box,
                'templateArr' => $templateArr,
                'channelTypeArr' => $channelTypeArr,
            ]
        );
        Box::end(); ?>
    </div>
</div>
