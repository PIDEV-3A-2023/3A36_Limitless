<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302161848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog ADD joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_C0155143A9E2D76C ON blog (joueur_id)');
        $this->addSql('ALTER TABLE commentaire ADD joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCA9E2D76C ON commentaire (joueur_id)');
        $this->addSql('ALTER TABLE dislike_blog ADD joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dislike_blog ADD CONSTRAINT FK_990F9EADA9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_990F9EADA9E2D76C ON dislike_blog (joueur_id)');
        $this->addSql('ALTER TABLE like_blog ADD joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE like_blog ADD CONSTRAINT FK_19608D3DA9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_19608D3DA9E2D76C ON like_blog (joueur_id)');
        $this->addSql('ALTER TABLE report ADD joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_C42F7784A9E2D76C ON report (joueur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143A9E2D76C');
        $this->addSql('DROP INDEX IDX_C0155143A9E2D76C ON blog');
        $this->addSql('ALTER TABLE blog DROP joueur_id');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA9E2D76C');
        $this->addSql('DROP INDEX IDX_67F068BCA9E2D76C ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP joueur_id');
        $this->addSql('ALTER TABLE dislike_blog DROP FOREIGN KEY FK_990F9EADA9E2D76C');
        $this->addSql('DROP INDEX IDX_990F9EADA9E2D76C ON dislike_blog');
        $this->addSql('ALTER TABLE dislike_blog DROP joueur_id');
        $this->addSql('ALTER TABLE like_blog DROP FOREIGN KEY FK_19608D3DA9E2D76C');
        $this->addSql('DROP INDEX IDX_19608D3DA9E2D76C ON like_blog');
        $this->addSql('ALTER TABLE like_blog DROP joueur_id');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A9E2D76C');
        $this->addSql('DROP INDEX IDX_C42F7784A9E2D76C ON report');
        $this->addSql('ALTER TABLE report DROP joueur_id');
    }
}
