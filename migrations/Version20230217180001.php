<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217180001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeux_type_jeux (jeux_id INT NOT NULL, type_jeux_id INT NOT NULL, INDEX IDX_2AC9B904EC2AA9D2 (jeux_id), INDEX IDX_2AC9B904C40BC627 (type_jeux_id), PRIMARY KEY(jeux_id, type_jeux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_jeux (id INT AUTO_INCREMENT NOT NULL, nom_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeux_type_jeux ADD CONSTRAINT FK_2AC9B904EC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeux_type_jeux ADD CONSTRAINT FK_2AC9B904C40BC627 FOREIGN KEY (type_jeux_id) REFERENCES type_jeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeux DROP type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux_type_jeux DROP FOREIGN KEY FK_2AC9B904EC2AA9D2');
        $this->addSql('ALTER TABLE jeux_type_jeux DROP FOREIGN KEY FK_2AC9B904C40BC627');
        $this->addSql('DROP TABLE jeux_type_jeux');
        $this->addSql('DROP TABLE type_jeux');
        $this->addSql('ALTER TABLE jeux ADD type VARCHAR(255) NOT NULL');
    }
}
