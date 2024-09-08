<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908160026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE raid_register_specialization (raid_register_id INT NOT NULL, specialization_id INT NOT NULL, INDEX IDX_6C260341CF673C6A (raid_register_id), INDEX IDX_6C260341FA846217 (specialization_id), PRIMARY KEY(raid_register_id, specialization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE raid_register_specialization ADD CONSTRAINT FK_6C260341CF673C6A FOREIGN KEY (raid_register_id) REFERENCES raid_register (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE raid_register_specialization ADD CONSTRAINT FK_6C260341FA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE raid_register_specialization DROP FOREIGN KEY FK_6C260341CF673C6A');
        $this->addSql('ALTER TABLE raid_register_specialization DROP FOREIGN KEY FK_6C260341FA846217');
        $this->addSql('DROP TABLE raid_register_specialization');
    }
}
