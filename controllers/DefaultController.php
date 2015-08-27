<?php

namespace jhancock\markdowndocs;

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
        $is_dir = false;
        if(preg_match('/\.\./', $page)) {
            throw new ForbiddenHttpException('Access to this document is forbidden');
        }

        $file = Yii::getAlias("@app/docs/$page");
        if(is_dir($file)) {
            if(is_file("$file/index.md")) {
                $file = "$file/index.md";
                $is_dir = true;
            }
        }

        if(!file_exists($file)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'file'   => $file,
            'is_dir' => $is_dir,
        ]);
    }
}
