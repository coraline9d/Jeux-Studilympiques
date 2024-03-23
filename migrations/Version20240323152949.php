<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240323152949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer ADD offer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E53C674EE ON offer (offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E53C674EE');
        $this->addSql('DROP INDEX IDX_29D6873E53C674EE ON offer');
        $this->addSql('ALTER TABLE offer DROP offer_id');
    }
}
