<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629185532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analysis (id UUID NOT NULL, rating_id UUID NOT NULL, title VARCHAR(100) NOT NULL, instructions TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_33C730A32EFC6 ON analysis (rating_id)');
        $this->addSql('COMMENT ON COLUMN analysis.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analysis.rating_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE analysis ADD CONSTRAINT FK_33C730A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE domain DROP CONSTRAINT fk_a7a91e0ba32efc6');
        $this->addSql('DROP INDEX idx_a7a91e0ba32efc6');
        $this->addSql('UPDATE domain SET rating_id=NULL');
        $this->addSql('ALTER TABLE domain RENAME COLUMN rating_id TO analysis_id');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0B7941003F FOREIGN KEY (analysis_id) REFERENCES analysis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A7A91E0B7941003F ON domain (analysis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain DROP CONSTRAINT FK_A7A91E0B7941003F');
        $this->addSql('DROP TABLE analysis');
        $this->addSql('DROP INDEX IDX_A7A91E0B7941003F');
        $this->addSql('ALTER TABLE domain RENAME COLUMN analysis_id TO rating_id');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT fk_a7a91e0ba32efc6 FOREIGN KEY (rating_id) REFERENCES rating (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a7a91e0ba32efc6 ON domain (rating_id)');
    }
}
