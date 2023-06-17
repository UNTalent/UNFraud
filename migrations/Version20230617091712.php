<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617091712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain ADD whois_creation_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE domain ADD whois_expiration_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE domain ADD whois_update_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE domain ADD whois_owner VARCHAR(255) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN domain.whois_creation_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN domain.whois_expiration_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN domain.whois_update_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain DROP whois_creation_date');
        $this->addSql('ALTER TABLE domain DROP whois_expiration_date');
        $this->addSql('ALTER TABLE domain DROP whois_update_date');
        $this->addSql('ALTER TABLE domain DROP whois_owner');
    }
}
