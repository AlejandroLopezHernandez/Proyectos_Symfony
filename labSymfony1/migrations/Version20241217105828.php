<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217105828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cancion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, autor VARCHAR(255) NOT NULL, disco VARCHAR(255) NOT NULL, genero VARCHAR(255) NOT NULL, duracion INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lista (id INT AUTO_INCREMENT NOT NULL, canciones_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_FB9FEEED2E7FBA16 (canciones_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lista ADD CONSTRAINT FK_FB9FEEED2E7FBA16 FOREIGN KEY (canciones_id) REFERENCES cancion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lista DROP FOREIGN KEY FK_FB9FEEED2E7FBA16');
        $this->addSql('DROP TABLE cancion');
        $this->addSql('DROP TABLE lista');
    }
}
