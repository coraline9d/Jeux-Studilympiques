<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327152206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_payment DROP FOREIGN KEY FK_ABD3F884C3A3BB');
        $this->addSql('ALTER TABLE reservation_payment DROP FOREIGN KEY FK_ABD3F88B83297E7');
        $this->addSql('DROP TABLE reservation_payment');
        $this->addSql('ALTER TABLE reservation ADD payment_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE INDEX IDX_42C849554C3A3BB ON reservation (payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_payment (reservation_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', payment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_ABD3F88B83297E7 (reservation_id), INDEX IDX_ABD3F884C3A3BB (payment_id), PRIMARY KEY(reservation_id, payment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation_payment ADD CONSTRAINT FK_ABD3F884C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_payment ADD CONSTRAINT FK_ABD3F88B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554C3A3BB');
        $this->addSql('DROP INDEX IDX_42C849554C3A3BB ON reservation');
        $this->addSql('ALTER TABLE reservation DROP payment_id');
    }
}
