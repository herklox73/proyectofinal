<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209002419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Verificar si la columna 'user_id' ya existe en la tabla 'empresa'
        $this->addSql('
            SET @column_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                                  WHERE table_name = "empresa" AND column_name = "user_id");
            IF @column_exists = 0 THEN
                ALTER TABLE empresa ADD user_id INT NOT NULL;
                ALTER TABLE empresa ADD CONSTRAINT FK_B8D75A50A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
                CREATE UNIQUE INDEX UNIQ_B8D75A50A76ED395 ON empresa (user_id);
            END IF;
        ');
    }
    


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa DROP FOREIGN KEY FK_B8D75A50A76ED395');
        $this->addSql('DROP INDEX UNIQ_B8D75A50A76ED395 ON empresa');
        $this->addSql('ALTER TABLE empresa DROP user_id');
    }
}
