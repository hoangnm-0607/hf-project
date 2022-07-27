<?php

namespace App\EventListener;

use Doctrine\DBAL\Event\ConnectionEventArgs;


class  PdoInitializerListener
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        $args->getConnection()
            ->executeStatement("SET time_zone = 'Europe/Paris'");
    }
}
