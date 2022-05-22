<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220522004204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A8B31707C');
        $this->addSql('DROP INDEX IDX_E01FBE6A8B31707C ON images');
        $this->addSql('ALTER TABLE images CHANGE the_product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A4584665A ON images (product_id)');
        $this->addSql('ALTER TABLE product ADD image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A4584665A');
        $this->addSql('DROP INDEX IDX_E01FBE6A4584665A ON images');
        $this->addSql('ALTER TABLE images CHANGE product_id the_product_id INT NOT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A8B31707C FOREIGN KEY (the_product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A8B31707C ON images (the_product_id)');
        $this->addSql('ALTER TABLE product DROP image');
    }
}
