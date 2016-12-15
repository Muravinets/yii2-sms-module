<?php

namespace ihacklog\sms\controllers;

use yii\web\Controller;
use company\models\LoginForm;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
