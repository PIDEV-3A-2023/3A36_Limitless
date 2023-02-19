<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214092337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux CHANGE ref ref VARCHAR(8) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3755B50D146F3EA3 ON jeux (ref)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3755B50D146F3EA3 ON jeux');
        $this->addSql('ALTER TABLE jeux CHANGE ref ref VARCHAR(255) NOT NULL');
    }
}
