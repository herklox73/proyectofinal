<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209001541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa DROP FOREIGN KEY FK_B8D75A50A76ED395');
        $this->addSql('DROP INDEX IDX_B8D75A50A76ED395 ON empresa');
        $this->addSql('ALTER TABLE empresa DROP user_id, CHANGE foto_perfil foto_perfil VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE createdAt created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa ADD user_id INT NOT NULL, CHANGE foto_perfil foto_perfil VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE empresa ADD CONSTRAINT FK_B8D75A50A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B8D75A50A76ED395 ON empresa (user_id)');
        $this->addSql('ALTER TABLE user CHANGE created_at createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
