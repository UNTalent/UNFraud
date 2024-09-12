<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912132859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resource (id UUID NOT NULL, collection_id UUID NOT NULL, title VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC91F416514956FD ON resource (collection_id)');
        $this->addSql('COMMENT ON COLUMN resource.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN resource.collection_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE resource_collection (id UUID NOT NULL, name VARCHAR(50) NOT NULL, description TEXT NOT NULL, weight_in_menu INT DEFAULT NULL, emoji VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN resource_collection.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416514956FD FOREIGN KEY (collection_id) REFERENCES resource_collection (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_status ADD emoji VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F416514956FD');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_collection');
        $this->addSql('ALTER TABLE complaint_status DROP emoji');
    }
}
