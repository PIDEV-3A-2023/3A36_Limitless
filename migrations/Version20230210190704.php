<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210190704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_jeux (id INT AUTO_INCREMENT NOT NULL, nom_cat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeux_categorie_jeux (jeux_id INT NOT NULL, categorie_jeux_id INT NOT NULL, INDEX IDX_B819830BEC2AA9D2 (jeux_id), INDEX IDX_B819830BB3A90770 (categorie_jeux_id), PRIMARY KEY(jeux_id, categorie_jeux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeux_categorie_jeux ADD CONSTRAINT FK_B819830BEC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeux_categorie_jeux ADD CONSTRAINT FK_B819830BB3A90770 FOREIGN KEY (categorie_jeux_id) REFERENCES categorie_jeux (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux_categorie_jeux DROP FOREIGN KEY FK_B819830BEC2AA9D2');
        $this->addSql('ALTER TABLE jeux_categorie_jeux DROP FOREIGN KEY FK_B819830BB3A90770');
        $this->addSql('DROP TABLE categorie_jeux');
        $this->addSql('DROP TABLE jeux_categorie_jeux');
    }
}
