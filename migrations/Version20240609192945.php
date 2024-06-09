<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240609192945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE complaint (id UUID NOT NULL, status_id UUID NOT NULL, email VARCHAR(200) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, money_sent DOUBLE PRECISION DEFAULT NULL, country VARCHAR(100) NOT NULL, code VARCHAR(200) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F2732B56BF700BD ON complaint (status_id)');
        $this->addSql('COMMENT ON COLUMN complaint.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN complaint.status_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN complaint.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE complaint_report (id UUID NOT NULL, complaint_id UUID NOT NULL, report_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E66CBA46EDAE188E ON complaint_report (complaint_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E66CBA464BD2A4C0 ON complaint_report (report_id)');
        $this->addSql('COMMENT ON COLUMN complaint_report.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN complaint_report.complaint_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN complaint_report.report_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE complaint_status (id UUID NOT NULL, name VARCHAR(100) NOT NULL, has_replied BOOLEAN NOT NULL, has_sent_sensitive_data BOOLEAN NOT NULL, has_sent_money BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN complaint_status.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B56BF700BD FOREIGN KEY (status_id) REFERENCES complaint_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_report ADD CONSTRAINT FK_E66CBA46EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_report ADD CONSTRAINT FK_E66CBA464BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B56BF700BD');
        $this->addSql('ALTER TABLE complaint_report DROP CONSTRAINT FK_E66CBA46EDAE188E');
        $this->addSql('ALTER TABLE complaint_report DROP CONSTRAINT FK_E66CBA464BD2A4C0');
        $this->addSql('DROP TABLE complaint');
        $this->addSql('DROP TABLE complaint_report');
        $this->addSql('DROP TABLE complaint_status');
    }
}
