<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327102457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_offer (reservation_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', offer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_6AA95799B83297E7 (reservation_id), INDEX IDX_6AA9579953C674EE (offer_id), PRIMARY KEY(reservation_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_offer ADD CONSTRAINT FK_6AA95799B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_offer ADD CONSTRAINT FK_6AA9579953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD payment_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE ticket ticket VARCHAR(2083) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C849554C3A3BB ON reservation (payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_offer DROP FOREIGN KEY FK_6AA95799B83297E7');
        $this->addSql('ALTER TABLE reservation_offer DROP FOREIGN KEY FK_6AA9579953C674EE');
        $this->addSql('DROP TABLE reservation_offer');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554C3A3BB');
        $this->addSql('DROP INDEX UNIQ_42C849554C3A3BB ON reservation');
        $this->addSql('ALTER TABLE reservation DROP payment_id, CHANGE ticket ticket LONGBLOB DEFAULT NULL');
    }
}
