<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926131117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_profession (id INT AUTO_INCREMENT NOT NULL, character_id INT NOT NULL, profession_id INT NOT NULL, specialization_id INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_B1FB4EF1136BE75 (character_id), INDEX IDX_B1FB4EFFDEF8996 (profession_id), INDEX IDX_B1FB4EFFA846217 (specialization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_profession_patron (character_profession_id INT NOT NULL, patron_id INT NOT NULL, INDEX IDX_5DC4A511C95E34EF (character_profession_id), INDEX IDX_5DC4A511DBD5322 (patron_id), PRIMARY KEY(character_profession_id, patron_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patron (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E5F5425D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profession (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BA930D695E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profession_specialization (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F668D3B45E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_profession ADD CONSTRAINT FK_B1FB4EF1136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE character_profession ADD CONSTRAINT FK_B1FB4EFFDEF8996 FOREIGN KEY (profession_id) REFERENCES profession (id)');
        $this->addSql('ALTER TABLE character_profession ADD CONSTRAINT FK_B1FB4EFFA846217 FOREIGN KEY (specialization_id) REFERENCES profession_specialization (id)');
        $this->addSql('ALTER TABLE character_profession_patron ADD CONSTRAINT FK_5DC4A511C95E34EF FOREIGN KEY (character_profession_id) REFERENCES character_profession (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_profession_patron ADD CONSTRAINT FK_5DC4A511DBD5322 FOREIGN KEY (patron_id) REFERENCES patron (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_profession DROP FOREIGN KEY FK_B1FB4EF1136BE75');
        $this->addSql('ALTER TABLE character_profession DROP FOREIGN KEY FK_B1FB4EFFDEF8996');
        $this->addSql('ALTER TABLE character_profession DROP FOREIGN KEY FK_B1FB4EFFA846217');
        $this->addSql('ALTER TABLE character_profession_patron DROP FOREIGN KEY FK_5DC4A511C95E34EF');
        $this->addSql('ALTER TABLE character_profession_patron DROP FOREIGN KEY FK_5DC4A511DBD5322');
        $this->addSql('DROP TABLE character_profession');
        $this->addSql('DROP TABLE character_profession_patron');
        $this->addSql('DROP TABLE patron');
        $this->addSql('DROP TABLE profession');
        $this->addSql('DROP TABLE profession_specialization');
    }
}
