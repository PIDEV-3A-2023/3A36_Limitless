<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306092219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, datenai DATE NOT NULL, pprofile VARCHAR(255) NOT NULL, is_banned TINYINT(1) DEFAULT NULL, ign VARCHAR(255) NOT NULL, wins INT DEFAULT NULL, loses INT DEFAULT NULL, created_at DATE NOT NULL, UNIQUE INDEX UNIQ_FD71A9C5E7927C74 (email), UNIQUE INDEX UNIQ_FD71A9C538F19882 (ign), FULLTEXT INDEX joueur (nom, prenom, email, ign), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES joueur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143A9E2D76C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA9E2D76C');
        $this->addSql('ALTER TABLE dislike_blog DROP FOREIGN KEY FK_990F9EADA9E2D76C');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15A9E2D76C');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB77805A9E2D76C');
        $this->addSql('ALTER TABLE jaimepas DROP FOREIGN KEY FK_AA72DC24A9E2D76C');
        $this->addSql('ALTER TABLE like_blog DROP FOREIGN KEY FK_19608D3DA9E2D76C');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DA76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A9E2D76C');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE reset_password_request');
    }
}
