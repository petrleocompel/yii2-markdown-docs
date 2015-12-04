# Yii2 Markdown docs

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist petrleocompel/yii2-markdown-docs "*"
```
or run

```
composer require petrleocompel/yii2-markdown-docs
```

or add

```
"petrleocompel/yii2-markdown-docs": "*"
```

to the require section of your `composer.json` file.

## Configuration

Enable the module in your confguration:

```
'modules' => [
    'docs' => [
        'class' => 'petrleocompel\yii2\markdowndocs\Module',
        // You can set custom parserClass
        //'parserClass' => 'cebe\markdown\GithubMarkdown'
    ],
],
```

### Routing

Add a rule to your url manager component:

```
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName'  => false,
    'rules' => [
        ...
        [
            'pattern'      => 'doc<page:.+>',
            'route'        => 'doc/default/view',
            'encodeParams' => false
        ],
        ...
    ],
],
```

Your documentation would then be accessible under your application's `/doc` URL.

> You can change this url but if your `@app` is your `@webroot` then if you will change it to `/docs` there could be collision.
 
So for compatibility i recommend to use `/doc`

## Usage

This module assumes your documentation lives under the `@app/docs` alias.

### Make doc
Under `@app/docs` you should make your md files. You dont have to create listing its automatic.

#### Titles of doc
On every 1 line of file should be `#` means Heading 1