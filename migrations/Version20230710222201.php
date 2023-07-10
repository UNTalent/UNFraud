<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230710222201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_data__dns_records ADD apply_analysis_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN domain_data__dns_records.apply_analysis_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE domain_data__dns_records ADD CONSTRAINT FK_64CBDEF523F61883 FOREIGN KEY (apply_analysis_id) REFERENCES analysis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_64CBDEF523F61883 ON domain_data__dns_records (apply_analysis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_data__dns_records DROP CONSTRAINT FK_64CBDEF523F61883');
        $this->addSql('DROP INDEX IDX_64CBDEF523F61883');
        $this->addSql('ALTER TABLE domain_data__dns_records DROP apply_analysis_id');
    }
}
