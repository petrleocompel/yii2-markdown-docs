<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $file string */

$meta = [];
$parserClass = jhancock\markdowndocs\Module::getInstance()->parserClass;
$parser = new $parserClass;

if(is_file($file)) {
    $stop = 1;
    // Read in the file, allow for metadata at the top of the file
    $fh = fopen($file, 'r');
    if(!$fh) {
        throw new \Exception("Unable to open $file");
    }

    do {
        $line = trim(fgets($fh));

        if(preg_match('/^(.+?):\s+(.+)$/', $line, $matches)) {
            $meta[$matches[1]] = $matches[2];
        }
    } while (strlen($line) > 0);

    $markdown = fread($fh, filesize($file) - ftell($fh));
    fclose($fh);
} else {
    $stop = 0;
    $contents = scandir($file);
    $markdown = '';
    $entries = [];
    foreach($contents as $f) {
        if($f == '.' || $f == '..') {
            continue;
        }
        if(preg_match('/(.+)\.md$/', $f, $matches)) {
            $title = ucwords($matches[1]);
        } else {
            $title = ucwords($f);
        }
        $temp_meta = [];
        if(is_file("$file/$f")) {
            $fh = fopen("$file/$f", 'r');
            do {
                $line = trim(fgets($fh));

                if(preg_match('/^(.+?):\s+(.+)$/', $line, $matches)) {
                    $temp_meta[$matches[1]] = $matches[2];
                }
            } while(strlen($line) > 0);
            fclose($fh);
            $title = isset($temp_meta['Title']) ? $temp_meta['Title'] : $title;
        }

        $entries[] = "* [$title](" . Yii::$app->request->absoluteUrl . "/$f)";
    }
    sort($entries);
    $markdown = implode("\n", $entries);
    $meta['Title'] = 'Index';
}

// Build up the breadcrumbs
$this->params['breadcrumbs'][] = [
    'label' => 'Documentation',
    'url' => ['view', 'page' => '/'],
];
$docpath = Yii::getAlias("@app/docs/");
$delta = substr($file, strlen($docpath) + 1);
$pieces = explode('/', $delta);
for($i=0; $i<count($pieces) - $stop; $i++) {
    if(strlen($pieces[$i]) > 0) {
        $this->params['breadcrumbs'][] = [
            'label' => ucwords(str_replace('_', ' ', $pieces[$i])),
            'url' => ['view', 'page' => '/' . implode('/', array_slice($pieces, 0, $i+1))],
        ];
    }
}

$this->title = isset($meta['Title']) ? $meta['Title'] : 'unknown title';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="doc-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="markdown-doc">
        <?= $parser->parse($markdown); ?>
    </div>
</div>
