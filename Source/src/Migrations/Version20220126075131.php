<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126075131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modifies crondispatch table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("ALTER TABLE crondispatch ADD COLUMN `parameters` varchar(255) NOT NULL after `jobname`, DROP PRIMARY KEY, ADD PRIMARY KEY (`jobname`, `parameters`)");
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE crondispatch DROP COLUMN `parameters`, DROP PRIMARY KEY, ADD PRIMARY KEY (`jobname`)");;
    }
}
