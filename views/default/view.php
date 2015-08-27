<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $file string */

// Read in the file, allow for metadata at the top of the file
$fh = fopen($file, 'r');
if(!$fh) {
    throw new \Exception("Unable to open $file");
}

$meta = [];
do {
    $line = trim(fgets($fh));

    if(preg_match('/^(.+?):\s+(.+)$/', $line, $matches)) {
        $meta[$matches[1]] = $matches[2];
    }
} while (strlen($line) > 0);

$markdown = fread($fh, filesize($file) - ftell($fh));
fclose($fh);

// Build up the breadcrumbs
$this->params['breadcrumbs'][] = [
    'label' => 'Documentation',
    'url' => ['view', 'page' => '/'],
];
$docpath = Yii::getAlias("@app/docs/");
$delta = substr($file, strlen($docpath) + 1);
print "delta = $delta\n";
$pieces = explode('/', $delta);
$stop = $is_dir ? 2 : 1;
print "stop = $stop\n";
for($i=0; $i<count($pieces) - $stop; $i++) {
    $this->params['breadcrumbs'][] = [
        'label' => ucwords(str_replace('_', ' ', $pieces[$i])),
        'url' => ['view', 'page' => '/' . implode('/', array_slice($pieces, 0, $i+1))],
    ];
}

$this->title = isset($meta['Title']) ? $meta['Title'] : 'unknown title';
$this->params['breadcrumbs'][] = $this->title;

$parser = new \cebe\markdown\GithubMarkdown();

?>
<div class="doc-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="markdown-doc">
        <?= $parser->parse($markdown); ?>
    </div>
</div>
