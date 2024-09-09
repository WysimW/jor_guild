<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909073613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_937AB034A76ED395 (user_id), INDEX IDX_937AB0348F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_role (character_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_40959EF21136BE75 (character_id), INDEX IDX_40959EF2D60322AC (role_id), PRIMARY KEY(character_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE raid (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, capacity INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE raid_register (id INT AUTO_INCREMENT NOT NULL, raid_id INT DEFAULT NULL, registred_character_id INT DEFAULT NULL, registered_date DATETIME NOT NULL, INDEX IDX_28EB2479C55ABC9 (raid_id), INDEX IDX_28EB247349F6AAE (registred_character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE raid_register_specialization (raid_register_id INT NOT NULL, specialization_id INT NOT NULL, INDEX IDX_6C260341CF673C6A (raid_register_id), INDEX IDX_6C260341FA846217 (specialization_id), PRIMARY KEY(raid_register_id, specialization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialization (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, spe_role_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9ED9F26A8F5EA509 (classe_id), INDEX IDX_9ED9F26A2B93D8D5 (spe_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB0348F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE character_role ADD CONSTRAINT FK_40959EF21136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_role ADD CONSTRAINT FK_40959EF2D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE raid_register ADD CONSTRAINT FK_28EB2479C55ABC9 FOREIGN KEY (raid_id) REFERENCES raid (id)');
        $this->addSql('ALTER TABLE raid_register ADD CONSTRAINT FK_28EB247349F6AAE FOREIGN KEY (registred_character_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE raid_register_specialization ADD CONSTRAINT FK_6C260341CF673C6A FOREIGN KEY (raid_register_id) REFERENCES raid_register (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE raid_register_specialization ADD CONSTRAINT FK_6C260341FA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialization ADD CONSTRAINT FK_9ED9F26A8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE specialization ADD CONSTRAINT FK_9ED9F26A2B93D8D5 FOREIGN KEY (spe_role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034A76ED395');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB0348F5EA509');
        $this->addSql('ALTER TABLE character_role DROP FOREIGN KEY FK_40959EF21136BE75');
        $this->addSql('ALTER TABLE character_role DROP FOREIGN KEY FK_40959EF2D60322AC');
        $this->addSql('ALTER TABLE raid_register DROP FOREIGN KEY FK_28EB2479C55ABC9');
        $this->addSql('ALTER TABLE raid_register DROP FOREIGN KEY FK_28EB247349F6AAE');
        $this->addSql('ALTER TABLE raid_register_specialization DROP FOREIGN KEY FK_6C260341CF673C6A');
        $this->addSql('ALTER TABLE raid_register_specialization DROP FOREIGN KEY FK_6C260341FA846217');
        $this->addSql('ALTER TABLE specialization DROP FOREIGN KEY FK_9ED9F26A8F5EA509');
        $this->addSql('ALTER TABLE specialization DROP FOREIGN KEY FK_9ED9F26A2B93D8D5');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE character_role');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE raid');
        $this->addSql('DROP TABLE raid_register');
        $this->addSql('DROP TABLE raid_register_specialization');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE specialization');
        $this->addSql('DROP TABLE user');
    }
}
