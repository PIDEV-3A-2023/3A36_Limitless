<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305170852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, joueur_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom_equipe VARCHAR(255) NOT NULL, description_equipe VARCHAR(255) NOT NULL, nb_joueurs INT NOT NULL, logo_equipe VARCHAR(255) NOT NULL, site_web VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, rating INT NOT NULL, INDEX IDX_2449BA15A9E2D76C (joueur_id), INDEX IDX_2449BA15A76ED395 (user_id), FULLTEXT INDEX equipe (nom_equipe, description_equipe), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jaime (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, joueur_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_3CB778056D861B89 (equipe_id), INDEX IDX_3CB77805A9E2D76C (joueur_id), INDEX IDX_3CB77805A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jaimepas (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, joueur_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_AA72DC246D861B89 (equipe_id), INDEX IDX_AA72DC24A9E2D76C (joueur_id), INDEX IDX_AA72DC24A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likeseq (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, typel INT NOT NULL, INDEX IDX_75A4EE516D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, id_equipe_id INT DEFAULT NULL, nom_sponsor VARCHAR(255) NOT NULL, description_sponsor VARCHAR(255) NOT NULL, logo_sponsor VARCHAR(255) NOT NULL, site_webs VARCHAR(255) NOT NULL, date_creationn DATE NOT NULL, INDEX IDX_818CC9D4EDB3A7AE (id_equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB778056D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB77805A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB77805A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE jaimepas ADD CONSTRAINT FK_AA72DC246D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE jaimepas ADD CONSTRAINT FK_AA72DC24A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE jaimepas ADD CONSTRAINT FK_AA72DC24A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likeseq ADD CONSTRAINT FK_75A4EE516D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D4EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15A9E2D76C');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15A76ED395');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB778056D861B89');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB77805A9E2D76C');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB77805A76ED395');
        $this->addSql('ALTER TABLE jaimepas DROP FOREIGN KEY FK_AA72DC246D861B89');
        $this->addSql('ALTER TABLE jaimepas DROP FOREIGN KEY FK_AA72DC24A9E2D76C');
        $this->addSql('ALTER TABLE jaimepas DROP FOREIGN KEY FK_AA72DC24A76ED395');
        $this->addSql('ALTER TABLE likeseq DROP FOREIGN KEY FK_75A4EE516D861B89');
        $this->addSql('ALTER TABLE sponsor DROP FOREIGN KEY FK_818CC9D4EDB3A7AE');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE jaime');
        $this->addSql('DROP TABLE jaimepas');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE likeseq');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
