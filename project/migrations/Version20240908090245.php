<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908090245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialization (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9ED9F26A8F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE specialization ADD CONSTRAINT FK_9ED9F26A8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE `character` ADD classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB0348F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_937AB0348F5EA509 ON `character` (classe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB0348F5EA509');
        $this->addSql('ALTER TABLE specialization DROP FOREIGN KEY FK_9ED9F26A8F5EA509');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE specialization');
        $this->addSql('DROP INDEX IDX_937AB0348F5EA509 ON `character`');
        $this->addSql('ALTER TABLE `character` DROP classe_id');
    }
}
