<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227110126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dislike_blog (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_990F9EADDAE07E97 (blog_id), INDEX IDX_990F9EADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dislike_blog ADD CONSTRAINT FK_990F9EADDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE dislike_blog ADD CONSTRAINT FK_990F9EADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dislike_blog DROP FOREIGN KEY FK_990F9EADDAE07E97');
        $this->addSql('ALTER TABLE dislike_blog DROP FOREIGN KEY FK_990F9EADA76ED395');
        $this->addSql('DROP TABLE dislike_blog');
    }
}
