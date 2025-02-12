<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211011704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY curriculum_ibfk_1');
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, nombre_empresa VARCHAR(255) NOT NULL, ruc VARCHAR(20) NOT NULL, persona_encargada VARCHAR(255) NOT NULL, contacto VARCHAR(20) NOT NULL, ubicacion VARCHAR(255) NOT NULL, direccion VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B8D75A502EC7D87D (ruc), UNIQUE INDEX UNIQ_B8D75A50DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oferta_laboral (id INT AUTO_INCREMENT NOT NULL, empresa_id INT NOT NULL, cargo VARCHAR(255) NOT NULL, tipo_contacto VARCHAR(50) NOT NULL, canton VARCHAR(100) NOT NULL, parroquia VARCHAR(100) NOT NULL, remuneracion NUMERIC(10, 2) NOT NULL, jornada VARCHAR(50) NOT NULL, area_estudios VARCHAR(255) NOT NULL, contacto VARCHAR(20) NOT NULL, fecha_publicacion DATETIME NOT NULL, INDEX IDX_EB791463521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postulacion (id INT AUTO_INCREMENT NOT NULL, postulante_id INT NOT NULL, oferta_id INT NOT NULL, fecha_postulacion DATETIME NOT NULL, estado VARCHAR(20) DEFAULT \'pendiente\' NOT NULL, INDEX IDX_17B321BDCCC19F66 (postulante_id), INDEX IDX_17B321BDFAFBF624 (oferta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postulante (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nombres VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, genero VARCHAR(20) NOT NULL, edad INT NOT NULL, direccion VARCHAR(255) NOT NULL, cedula VARCHAR(20) NOT NULL, telefono VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_1C949887A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empresa ADD CONSTRAINT FK_B8D75A50DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oferta_laboral ADD CONSTRAINT FK_EB791463521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE postulacion ADD CONSTRAINT FK_17B321BDCCC19F66 FOREIGN KEY (postulante_id) REFERENCES postulante (id)');
        $this->addSql('ALTER TABLE postulacion ADD CONSTRAINT FK_17B321BDFAFBF624 FOREIGN KEY (oferta_id) REFERENCES oferta_laboral (id)');
        $this->addSql('ALTER TABLE postulante ADD CONSTRAINT FK_1C949887A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postulantes DROP FOREIGN KEY postulantes_ibfk_1');
        $this->addSql('ALTER TABLE empresas DROP FOREIGN KEY empresas_ibfk_1');
        $this->addSql('ALTER TABLE postulaciones DROP FOREIGN KEY postulaciones_ibfk_1');
        $this->addSql('ALTER TABLE postulaciones DROP FOREIGN KEY postulaciones_ibfk_2');
        $this->addSql('ALTER TABLE ofertas_laborales DROP FOREIGN KEY ofertas_laborales_ibfk_1');
        $this->addSql('DROP TABLE postulantes');
        $this->addSql('DROP TABLE empresas');
        $this->addSql('DROP TABLE postulaciones');
        $this->addSql('DROP TABLE usuarios');
        $this->addSql('DROP TABLE ofertas_laborales');
        $this->addSql('ALTER TABLE curriculum CHANGE descripcion descripcion LONGTEXT DEFAULT NULL, CHANGE fecha_subida fecha_subida DATETIME NOT NULL');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C3CCC19F66 FOREIGN KEY (postulante_id) REFERENCES postulante (id)');
        $this->addSql('ALTER TABLE curriculum RENAME INDEX postulante_id TO UNIQ_7BE2A7C3CCC19F66');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C3CCC19F66');
        $this->addSql('CREATE TABLE postulantes (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, nombres VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, apellidos VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, genero VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, edad INT NOT NULL, direccion VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, cedula VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, telefono VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX usuario_id (usuario_id), UNIQUE INDEX cedula (cedula), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE empresas (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, nombre_empresa VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, ruc VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, persona_encargada VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, contacto VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, ubicacion VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, direccion VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX usuario_id (usuario_id), UNIQUE INDEX ruc (ruc), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE postulaciones (id INT AUTO_INCREMENT NOT NULL, postulante_id INT NOT NULL, oferta_id INT NOT NULL, fecha_postulacion DATETIME DEFAULT CURRENT_TIMESTAMP, estado VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'pendiente\' COLLATE `utf8mb4_0900_ai_ci`, INDEX postulante_id (postulante_id), INDEX oferta_id (oferta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, tipo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ofertas_laborales (id INT AUTO_INCREMENT NOT NULL, empresa_id INT NOT NULL, cargo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, tipo_contrato VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, canton VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, parroquia VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, remuneracion NUMERIC(10, 2) NOT NULL, jornada VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, area_estudios VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, contacto VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX empresa_id (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE postulantes ADD CONSTRAINT postulantes_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE empresas ADD CONSTRAINT empresas_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE postulaciones ADD CONSTRAINT postulaciones_ibfk_1 FOREIGN KEY (postulante_id) REFERENCES postulantes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE postulaciones ADD CONSTRAINT postulaciones_ibfk_2 FOREIGN KEY (oferta_id) REFERENCES ofertas_laborales (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ofertas_laborales ADD CONSTRAINT ofertas_laborales_ibfk_1 FOREIGN KEY (empresa_id) REFERENCES empresas (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE empresa DROP FOREIGN KEY FK_B8D75A50DB38439E');
        $this->addSql('ALTER TABLE oferta_laboral DROP FOREIGN KEY FK_EB791463521E1991');
        $this->addSql('ALTER TABLE postulacion DROP FOREIGN KEY FK_17B321BDCCC19F66');
        $this->addSql('ALTER TABLE postulacion DROP FOREIGN KEY FK_17B321BDFAFBF624');
        $this->addSql('ALTER TABLE postulante DROP FOREIGN KEY FK_1C949887A76ED395');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP TABLE oferta_laboral');
        $this->addSql('DROP TABLE postulacion');
        $this->addSql('DROP TABLE postulante');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE curriculum CHANGE descripcion descripcion TEXT DEFAULT NULL, CHANGE fecha_subida fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT curriculum_ibfk_1 FOREIGN KEY (postulante_id) REFERENCES postulantes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE curriculum RENAME INDEX uniq_7be2a7c3ccc19f66 TO postulante_id');
    }
}
