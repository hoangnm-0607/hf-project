<?php

namespace App\Document\Areabricks;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class EmailHeader extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'Email Header';
    }

    public function getDescription()
    {
        return 'Contains the Hansefit Logo and an optional partner Logo';
    }

}
