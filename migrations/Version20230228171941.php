<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228171941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom_equipe VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeu (id INT AUTO_INCREMENT NOT NULL, nom_jeu VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeu_type (jeu_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B1B14C058C9E392E (jeu_id), INDEX IDX_B1B14C05C54C8C93 (type_id), PRIMARY KEY(jeu_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, id_tournoi_id INT NOT NULL, equipe1_id INT DEFAULT NULL, equipe2_id INT DEFAULT NULL, tour_actuel VARCHAR(255) DEFAULT NULL, score_equipe_a INT DEFAULT NULL, score_equipe_b INT DEFAULT NULL, date_creation DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_62615BA538DF7DD (id_tournoi_id), INDEX IDX_62615BA4265900C (equipe1_id), INDEX IDX_62615BA50D03FE2 (equipe2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, jeu_id INT NOT NULL, nom_tournoi VARCHAR(255) DEFAULT NULL, participant_total INT DEFAULT NULL, nom_hote VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, prix INT DEFAULT NULL, type_tournoi VARCHAR(255) DEFAULT NULL, image_tournoi VARCHAR(255) DEFAULT NULL, date_creation DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_18AFD9DF8C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, typejeu VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, mdp VARCHAR(32) NOT NULL, datenai DATE DEFAULT NULL, pays VARCHAR(255) DEFAULT NULL, pprofile VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeu_type ADD CONSTRAINT FK_B1B14C058C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_type ADD CONSTRAINT FK_B1B14C05C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA538DF7DD FOREIGN KEY (id_tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA4265900C FOREIGN KEY (equipe1_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA50D03FE2 FOREIGN KEY (equipe2_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DF8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu_type DROP FOREIGN KEY FK_B1B14C058C9E392E');
        $this->addSql('ALTER TABLE jeu_type DROP FOREIGN KEY FK_B1B14C05C54C8C93');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA538DF7DD');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA4265900C');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA50D03FE2');
        $this->addSql('ALTER TABLE tournoi DROP FOREIGN KEY FK_18AFD9DF8C9E392E');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE jeu');
        $this->addSql('DROP TABLE jeu_type');
        $this->addSql('DROP TABLE matches');
        $this->addSql('DROP TABLE tournoi');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
