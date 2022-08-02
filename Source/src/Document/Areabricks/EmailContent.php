<?php

namespace App\Document\Areabricks;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class EmailContent extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'Email Content';
    }

    public function getDescription()
    {
        return 'Contains a WYSIWYG element';
    }

}
