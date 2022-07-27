<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104103155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modifies crondispatch table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("ALTER TABLE crondispatch ADD `active` BOOL DEFAULT 0 NULL");
        $this->addSql("ALTER TABLE crondispatch CHANGE `starttime` `timestamp` timestamp DEFAULT current_timestamp() NOT NULL");
        $this->addSql("ALTER TABLE crondispatch CHANGE `timestamp` `timestamp` timestamp DEFAULT current_timestamp() NOT NULL AFTER `active`");

    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE crondispatch DROP COLUMN active");
        $this->addSql("ALTER TABLE crondispatch CHANGE `timestamp` starttime timestamp DEFAULT current_timestamp() NOT NULL");
    }
}
