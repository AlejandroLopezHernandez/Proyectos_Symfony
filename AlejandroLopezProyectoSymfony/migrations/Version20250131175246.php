<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131175246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario_cancion (usuario_id INT NOT NULL, cancion_id INT NOT NULL, INDEX IDX_9D44A5E7DB38439E (usuario_id), INDEX IDX_9D44A5E79B1D840F (cancion_id), PRIMARY KEY(usuario_id, cancion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usuario_cancion ADD CONSTRAINT FK_9D44A5E7DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_cancion ADD CONSTRAINT FK_9D44A5E79B1D840F FOREIGN KEY (cancion_id) REFERENCES cancion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_cancion DROP FOREIGN KEY FK_9D44A5E7DB38439E');
        $this->addSql('ALTER TABLE usuario_cancion DROP FOREIGN KEY FK_9D44A5E79B1D840F');
        $this->addSql('DROP TABLE usuario_cancion');
    }
}
