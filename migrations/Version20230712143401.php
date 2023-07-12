<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712143401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain ADD parent_domain_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN domain.parent_domain_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0BE014210B FOREIGN KEY (parent_domain_id) REFERENCES domain (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A7A91E0BE014210B ON domain (parent_domain_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain DROP CONSTRAINT FK_A7A91E0BE014210B');
        $this->addSql('DROP INDEX IDX_A7A91E0BE014210B');
        $this->addSql('ALTER TABLE domain DROP parent_domain_id');
    }
}
