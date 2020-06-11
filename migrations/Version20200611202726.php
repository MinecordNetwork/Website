<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611202726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adds notified_discord column to article table, together with index';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article ADD notified_discord TINYINT(1) NOT NULL');
        $this->addSql('CREATE INDEX IDX_23A0E661C6403F2 ON article (notified_discord)');
        $this->addSql('UPDATE article SET notified_discord = 1');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_23A0E661C6403F2 ON article');
        $this->addSql('ALTER TABLE article DROP notified_discord');
    }
}
