<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240917163500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boss ADD order_in_raid INT DEFAULT NULL');
        $this->addSql('ALTER TABLE raid ADD wlog_link VARCHAR(255) DEFAULT NULL, ADD wanalyzer_link VARCHAR(255) DEFAULT NULL, ADD wipefest_link VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boss DROP order_in_raid');
        $this->addSql('ALTER TABLE raid DROP wlog_link, DROP wanalyzer_link, DROP wipefest_link');
    }
}
