<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305222030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jaime (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, joueur_id INT DEFAULT NULL, INDEX IDX_3CB778056D861B89 (equipe_id), INDEX IDX_3CB77805A9E2D76C (joueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jaimepas (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, joueur_id INT DEFAULT NULL, INDEX IDX_AA72DC246D861B89 (equipe_id), INDEX IDX_AA72DC24A9E2D76C (joueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB778056D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB77805A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE jaimepas ADD CONSTRAINT FK_AA72DC246D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE jaimepas ADD CONSTRAINT FK_AA72DC24A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE equipe ADD joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_2449BA15A9E2D76C ON equipe (joueur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB778056D861B89');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB77805A9E2D76C');
        $this->addSql('ALTER TABLE jaimepas DROP FOREIGN KEY FK_AA72DC246D861B89');
        $this->addSql('ALTER TABLE jaimepas DROP FOREIGN KEY FK_AA72DC24A9E2D76C');
        $this->addSql('DROP TABLE jaime');
        $this->addSql('DROP TABLE jaimepas');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15A9E2D76C');
        $this->addSql('DROP INDEX IDX_2449BA15A9E2D76C ON equipe');
        $this->addSql('ALTER TABLE equipe DROP joueur_id');
    }
}
