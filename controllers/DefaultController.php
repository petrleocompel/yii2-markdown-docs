<?php

namespace petrleocompel\markdowndocs\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 *
 * @package petrleocompel\markdowndocs\controllers
 * @author    Petr Leo Compel <petr.compel@futuretek.cz>
 * @version   1.0.0
 */
class DefaultController extends Controller
{

    /**
     * Render Markdown
     *
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws InvalidParamException
     */
    public function actionIndex()
    {
        return $this->actionView('/');
    }

    /**
     * Render Markdown
     *
     * @param string $page name
     *
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws InvalidParamException
     */
    public function actionView($page)
    {
        if (preg_match('/\.\./', $page)) {
            throw new ForbiddenHttpException('Access to this document is forbidden');
        }
        $file = Yii::getAlias("@app/docs/$page", true);
        if (is_dir($file) && is_file("$file/index.md")) {
            $file = "$file/index.md";
        } else {
            $file .= '.md';
        }

        if (!file_exists($file)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render(
            'view',
            [
                'file' => $file,
            ]
        );
    }
}
