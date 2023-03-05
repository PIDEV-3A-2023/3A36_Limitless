<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303154401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_jeux (id INT AUTO_INCREMENT NOT NULL, nom_cat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_jeux_categorie_jeux (categorie_jeux_source INT NOT NULL, categorie_jeux_target INT NOT NULL, INDEX IDX_2F060467CF98DD5C (categorie_jeux_source), INDEX IDX_2F060467D67D8DD3 (categorie_jeux_target), PRIMARY KEY(categorie_jeux_source, categorie_jeux_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, refer VARCHAR(255) DEFAULT NULL, date_creation DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, refer VARCHAR(255) NOT NULL, date_commande DATETIME NOT NULL, prix_total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom_equipe VARCHAR(255) NOT NULL, description_equipe VARCHAR(255) NOT NULL, nb_joueurs INT NOT NULL, logo_equipe VARCHAR(255) NOT NULL, site_web VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE essai (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeux (id INT AUTO_INCREMENT NOT NULL, ref VARCHAR(8) NOT NULL, libelle VARCHAR(255) NOT NULL, logo_jeux VARCHAR(255) NOT NULL, image_jeux VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, note DOUBLE PRECISION NOT NULL, note_count INT NOT NULL, note_myonne DOUBLE PRECISION NOT NULL, total_note DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_3755B50D146F3EA3 (ref), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeux_categorie_jeux (jeux_id INT NOT NULL, categorie_jeux_id INT NOT NULL, INDEX IDX_B819830BEC2AA9D2 (jeux_id), INDEX IDX_B819830BB3A90770 (categorie_jeux_id), PRIMARY KEY(jeux_id, categorie_jeux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeux_type_jeux (jeux_id INT NOT NULL, type_jeux_id INT NOT NULL, INDEX IDX_2AC9B904EC2AA9D2 (jeux_id), INDEX IDX_2AC9B904C40BC627 (type_jeux_id), PRIMARY KEY(jeux_id, type_jeux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, user_id INT DEFAULT NULL, type INT DEFAULT NULL, INDEX IDX_49CA4E7DF347EFB (produit_id), INDEX IDX_49CA4E7DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likeseq (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, typel INT NOT NULL, INDEX IDX_75A4EE516D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, id_tournoi_id INT NOT NULL, equipe1_id INT DEFAULT NULL, equipe2_id INT DEFAULT NULL, tour_actuel VARCHAR(255) DEFAULT NULL, score_equipe_a INT DEFAULT NULL, score_equipe_b INT DEFAULT NULL, date_creation DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_62615BA538DF7DD (id_tournoi_id), INDEX IDX_62615BA4265900C (equipe1_id), INDEX IDX_62615BA50D03FE2 (equipe2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, refer VARCHAR(255) NOT NULL, type_card VARCHAR(20) NOT NULL, number_card VARCHAR(255) NOT NULL, cvc VARCHAR(10) NOT NULL, exp_month INT NOT NULL, exp_year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, participant VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_produit_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, quantite INT NOT NULL, prix VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, refer VARCHAR(255) DEFAULT NULL, date_produit DATETIME DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, nbre_etoile INT DEFAULT NULL, INDEX IDX_29A5EC2791FDB457 (categorie_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, id_equipe_id INT DEFAULT NULL, nom_sponsor VARCHAR(255) NOT NULL, description_sponsor VARCHAR(255) NOT NULL, logo_sponsor VARCHAR(255) NOT NULL, site_webs VARCHAR(255) NOT NULL, date_creationn DATE NOT NULL, INDEX IDX_818CC9D4EDB3A7AE (id_equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, jeu_id INT NOT NULL, nom_tournoi VARCHAR(255) DEFAULT NULL, participant_total INT DEFAULT NULL, nom_hote VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, prix INT DEFAULT NULL, type_tournoi VARCHAR(255) DEFAULT NULL, image_tournoi VARCHAR(255) DEFAULT NULL, date_creation DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_18AFD9DF8C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_jeux (id INT AUTO_INCREMENT NOT NULL, nom_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux ADD CONSTRAINT FK_2F060467CF98DD5C FOREIGN KEY (categorie_jeux_source) REFERENCES categorie_jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux ADD CONSTRAINT FK_2F060467D67D8DD3 FOREIGN KEY (categorie_jeux_target) REFERENCES categorie_jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeux_categorie_jeux ADD CONSTRAINT FK_B819830BEC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeux_categorie_jeux ADD CONSTRAINT FK_B819830BB3A90770 FOREIGN KEY (categorie_jeux_id) REFERENCES categorie_jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeux_type_jeux ADD CONSTRAINT FK_2AC9B904EC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeux_type_jeux ADD CONSTRAINT FK_2AC9B904C40BC627 FOREIGN KEY (type_jeux_id) REFERENCES type_jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DA76ED395 FOREIGN KEY (user_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE likeseq ADD CONSTRAINT FK_75A4EE516D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA538DF7DD FOREIGN KEY (id_tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA4265900C FOREIGN KEY (equipe1_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA50D03FE2 FOREIGN KEY (equipe2_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2791FDB457 FOREIGN KEY (categorie_produit_id) REFERENCES categorie_produit (id)');
        $this->addSql('ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D4EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DF8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux DROP FOREIGN KEY FK_2F060467CF98DD5C');
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux DROP FOREIGN KEY FK_2F060467D67D8DD3');
        $this->addSql('ALTER TABLE jeux_categorie_jeux DROP FOREIGN KEY FK_B819830BEC2AA9D2');
        $this->addSql('ALTER TABLE jeux_categorie_jeux DROP FOREIGN KEY FK_B819830BB3A90770');
        $this->addSql('ALTER TABLE jeux_type_jeux DROP FOREIGN KEY FK_2AC9B904EC2AA9D2');
        $this->addSql('ALTER TABLE jeux_type_jeux DROP FOREIGN KEY FK_2AC9B904C40BC627');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DF347EFB');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DA76ED395');
        $this->addSql('ALTER TABLE likeseq DROP FOREIGN KEY FK_75A4EE516D861B89');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA538DF7DD');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA4265900C');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA50D03FE2');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2791FDB457');
        $this->addSql('ALTER TABLE sponsor DROP FOREIGN KEY FK_818CC9D4EDB3A7AE');
        $this->addSql('ALTER TABLE tournoi DROP FOREIGN KEY FK_18AFD9DF8C9E392E');
        $this->addSql('DROP TABLE categorie_jeux');
        $this->addSql('DROP TABLE categorie_jeux_categorie_jeux');
        $this->addSql('DROP TABLE categorie_produit');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE essai');
        $this->addSql('DROP TABLE jeux');
        $this->addSql('DROP TABLE jeux_categorie_jeux');
        $this->addSql('DROP TABLE jeux_type_jeux');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE likeseq');
        $this->addSql('DROP TABLE matches');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE tournoi');
        $this->addSql('DROP TABLE type_jeux');
    }
}
