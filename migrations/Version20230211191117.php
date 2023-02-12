<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230211191117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sponsor ADD id_equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D4EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id)');
        $this->addSql('CREATE INDEX IDX_818CC9D4EDB3A7AE ON sponsor (id_equipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sponsor DROP FOREIGN KEY FK_818CC9D4EDB3A7AE');
        $this->addSql('DROP INDEX IDX_818CC9D4EDB3A7AE ON sponsor');
        $this->addSql('ALTER TABLE sponsor DROP id_equipe_id');
    }
}
