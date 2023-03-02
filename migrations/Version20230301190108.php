<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301190108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_jeux_categorie_jeux (categorie_jeux_source INT NOT NULL, categorie_jeux_target INT NOT NULL, INDEX IDX_2F060467CF98DD5C (categorie_jeux_source), INDEX IDX_2F060467D67D8DD3 (categorie_jeux_target), PRIMARY KEY(categorie_jeux_source, categorie_jeux_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux ADD CONSTRAINT FK_2F060467CF98DD5C FOREIGN KEY (categorie_jeux_source) REFERENCES categorie_jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux ADD CONSTRAINT FK_2F060467D67D8DD3 FOREIGN KEY (categorie_jeux_target) REFERENCES categorie_jeux (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux DROP FOREIGN KEY FK_2F060467CF98DD5C');
        $this->addSql('ALTER TABLE categorie_jeux_categorie_jeux DROP FOREIGN KEY FK_2F060467D67D8DD3');
        $this->addSql('DROP TABLE categorie_jeux_categorie_jeux');
    }
}
