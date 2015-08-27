<?php

namespace jhancock\markdowndocs\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    public function actionIndex() {
        return $this->actionView('/');
    }

    public function actionView($page)
    {
        if(preg_match('/\.\./', $page)) {
            throw new ForbiddenHttpException('Access to this document is forbidden');
        }

        $file = Yii::getAlias("@app/docs/$page");
        if(is_dir($file)) {
            if(is_file("$file/index.md")) {
                $file = "$file/index.md";
            }
        }

        if(!file_exists($file)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'file'   => $file,
        ]);
    }
}
