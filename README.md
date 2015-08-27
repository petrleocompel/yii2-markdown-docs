# yii2-markdown-docs

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jhancock/yii2-markdown-docs "*"
```

or add

```
"jhancock/yii2-markdown-docs": "*"
```

to the require section of your `composer.json` file.

## Configuration

Enable the module in your confguration:

```
'modules' => [
    'docs' => [
        'class' => 'jhancock\markdowndocs\Module',
        // Optional, defaults to cebe\markdown\GithubMarkdown
        'parserClass' => 'cebe\markdown\GithubMarkdown'
    ],
],
```

Add a rule to your url manager component:

```
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName'  => false,
    'rules' => [
        ...
        [
            'pattern'      => 'docs<page:.+>',
            'route'        => 'docs/default/view',
            'encodeParams' => false
        ],
        ...
    ],
],
```

Your documentation would then be accessible under your application's `/docs` URL.

## Usage

This module assumes your documentation lives under the `@app/docs` alias.
