<?php

namespace ihacklog\sms\controllers;

use yii\web\Controller;
use company\models\LoginForm;

class DefaultController extends Controller
{
    public function init() {
        parent::init();
        $model = new LoginForm();
        $model->setScenario('login');
        $model->username = 'webmaster';
        $model->password = 'webmaster';
        $model->login();
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
