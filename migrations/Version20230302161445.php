<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302161445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu MEDIUMTEXT NOT NULL, date_creation DATE NOT NULL, date_modification DATE NOT NULL, image_blog VARCHAR(255) NOT NULL, etat INT NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, date_modification DATE NOT NULL, nb_signaler INT NOT NULL, INDEX IDX_67F068BCDAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dislike_blog (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, INDEX IDX_990F9EADDAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE like_blog (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, INDEX IDX_19608D3DDAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, commentaire_id INT DEFAULT NULL, INDEX IDX_C42F7784BA9CD190 (commentaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE dislike_blog ADD CONSTRAINT FK_990F9EADDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE like_blog ADD CONSTRAINT FK_19608D3DDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCDAE07E97');
        $this->addSql('ALTER TABLE dislike_blog DROP FOREIGN KEY FK_990F9EADDAE07E97');
        $this->addSql('ALTER TABLE like_blog DROP FOREIGN KEY FK_19608D3DDAE07E97');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784BA9CD190');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE dislike_blog');
        $this->addSql('DROP TABLE like_blog');
        $this->addSql('DROP TABLE report');
    }
}
