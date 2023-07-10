<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230710174917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domain_data__dns_records (id UUID NOT NULL, record_type VARCHAR(20) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_record ON domain_data__dns_records (record_type, value)');
        $this->addSql('COMMENT ON COLUMN domain_data__dns_records.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE domain_data__dns_records__domains (id UUID NOT NULL, domain_id UUID NOT NULL, dns_record_id UUID NOT NULL, last_seen_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, first_seen_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_447A7F46115F0EE5 ON domain_data__dns_records__domains (domain_id)');
        $this->addSql('CREATE INDEX IDX_447A7F468476C1C9 ON domain_data__dns_records__domains (dns_record_id)');
        $this->addSql('COMMENT ON COLUMN domain_data__dns_records__domains.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_data__dns_records__domains.domain_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_data__dns_records__domains.dns_record_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_data__dns_records__domains.last_seen_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN domain_data__dns_records__domains.first_seen_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE domain_data__dns_records__domains ADD CONSTRAINT FK_447A7F46115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE domain_data__dns_records__domains ADD CONSTRAINT FK_447A7F468476C1C9 FOREIGN KEY (dns_record_id) REFERENCES domain_data__dns_records (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_data__dns_records__domains DROP CONSTRAINT FK_447A7F46115F0EE5');
        $this->addSql('ALTER TABLE domain_data__dns_records__domains DROP CONSTRAINT FK_447A7F468476C1C9');
        $this->addSql('DROP TABLE domain_data__dns_records');
        $this->addSql('DROP TABLE domain_data__dns_records__domains');
    }
}
