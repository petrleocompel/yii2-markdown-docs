<?php

namespace petrleocompel\yii2\markdowndocs;

/**
 * Class Module
 *
 * @package petrleocompel\yii2\markdowndocs
 * @author    Petr Leo Compel <petr.compel@futuretek.cz>
 * @version   1.0.0
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $parserClass = 'cebe\markdown\GithubMarkdown';
}
