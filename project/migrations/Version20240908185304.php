<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908185304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialization ADD spe_role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE specialization ADD CONSTRAINT FK_9ED9F26A2B93D8D5 FOREIGN KEY (spe_role_id) REFERENCES role (id)');
        $this->addSql('CREATE INDEX IDX_9ED9F26A2B93D8D5 ON specialization (spe_role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialization DROP FOREIGN KEY FK_9ED9F26A2B93D8D5');
        $this->addSql('DROP INDEX IDX_9ED9F26A2B93D8D5 ON specialization');
        $this->addSql('ALTER TABLE specialization DROP spe_role_id');
    }
}
