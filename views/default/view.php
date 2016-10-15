<?php

/**
 *  @var $this yii\web\View
 *  @var $file string
 */
use yii\helpers\Html;



$meta = [];
/** @var \petrleocompel\yii2\markdowndocs\Module $module */
$module = petrleocompel\yii2\markdowndocs\Module::getInstance();
/** @var bool $skipFirstHeading */
$skipFirstHeading = $module->skipFirstHeading;
    /** @var \cebe\markdown\GithubMarkdown $parser */
$parserClass = $module->parserClass;
$parser = new $parserClass;

if (is_file($file)) {
    $stop = 1;
    $markdown = file_get_contents($file);
    $first_line = explode("\n", $markdown)[0];
    if ($first_line[0] === '#') {
        $meta['title'] = substr($first_line, 1);

        if ($skipFirstHeading) {
            $markdown = substr($markdown, strpos($markdown, "\n")+1);
        }
    }

} else {
    $stop = 0;
    $contents = scandir($file);
    $markdown = '';
    $entries = [];
    foreach ($contents as $f) {
        if ($f === '.' || $f === '..') {
            continue;
        }
        if (preg_match('/(.+)\.md$/', $f, $matches)) {
            $title = ucwords($matches[1]);
        } else {
            $title = ucwords($f);
        }
        $temp_meta = [];
        if (is_file("$file/$f")) {
            $markdown = file_get_contents("$file/$f");
            $first_line = explode("\n", $markdown)[0];
            if ($first_line[0] === '#') {
                $title = substr($first_line, 1);
            }
        }
        $fileLink = substr($f, 0, -3);
        $entries[] = "* [$title](" . Yii::$app->request->absoluteUrl . "/$fileLink)";
    }
    sort($entries);
    $markdown = implode("\n", $entries);
    $meta['title'] = 'Index';
}

// Build up the breadcrumbs
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('petrleocompel-yii2-markdown-docs', 'Documentation'),
    'url' => ['view', 'page' => '/'],
];
$docpath = Yii::getAlias('@app/docs/');
$delta = substr($file, strlen($docpath) + 1);
$pieces = explode('/', $delta);
for ($i = 0; $i < count($pieces) - $stop; $i++) {
    if (strlen($pieces[$i]) > 0) {
        $this->params['breadcrumbs'][] = [
            'label' => ucwords(str_replace('_', ' ', $pieces[$i])),
            'url' => ['view', 'page' => '/' . implode('/', array_slice($pieces, 0, $i + 1))],
        ];
    }
}

$this->title = array_key_exists('title', $meta) ? $meta['title'] : Yii::t('petrleocompel-yii2-markdown-docs', 'Unknown title');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="doc-view">

    <div class="markdown-doc">
        <?= $parser->parse($markdown); ?>
    </div>
</div>
