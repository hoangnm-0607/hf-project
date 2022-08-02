<?php

namespace App\Document\Areabricks;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class EmailLink extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'Email Link';
    }

    public function getDescription()
    {
        return 'Contains a simple row with an automatic genereated link';
    }

}
