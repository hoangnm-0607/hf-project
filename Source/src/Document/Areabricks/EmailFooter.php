<?php

namespace App\Document\Areabricks;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class EmailFooter extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'Email Footer';
    }

    public function getDescription()
    {
        return 'Contains the default Hansefit Footer with an optional partner Logo';
    }
}
