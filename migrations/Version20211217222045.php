<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217222045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, origin_id INT DEFAULT NULL, path VARCHAR(1024) NOT NULL, mime_type VARCHAR(128) NOT NULL, size INT NOT NULL, width INT NOT NULL, height INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045F56A273CC ON image (origin_id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F56A273CC FOREIGN KEY (origin_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F56A273CC');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP TABLE image');
    }
}
