<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220521224805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_tshirt_sizes (product_id INT NOT NULL, tshirt_sizes_id INT NOT NULL, INDEX IDX_FF3BB1854584665A (product_id), INDEX IDX_FF3BB185FFE16FE4 (tshirt_sizes_id), PRIMARY KEY(product_id, tshirt_sizes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_shoes_sizes (product_id INT NOT NULL, shoes_sizes_id INT NOT NULL, INDEX IDX_296DBFCF4584665A (product_id), INDEX IDX_296DBFCFFF85C5DA (shoes_sizes_id), PRIMARY KEY(product_id, shoes_sizes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_tshirt_sizes ADD CONSTRAINT FK_FF3BB1854584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_tshirt_sizes ADD CONSTRAINT FK_FF3BB185FFE16FE4 FOREIGN KEY (tshirt_sizes_id) REFERENCES tshirt_sizes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_shoes_sizes ADD CONSTRAINT FK_296DBFCF4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_shoes_sizes ADD CONSTRAINT FK_296DBFCFFF85C5DA FOREIGN KEY (shoes_sizes_id) REFERENCES shoes_sizes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFFE16FE4');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFF85C5DA');
        $this->addSql('DROP INDEX IDX_D34A04ADFFE16FE4 ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADFF85C5DA ON product');
        $this->addSql('ALTER TABLE product DROP tshirt_sizes_id, DROP shoes_sizes_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_tshirt_sizes');
        $this->addSql('DROP TABLE product_shoes_sizes');
        $this->addSql('ALTER TABLE product ADD tshirt_sizes_id INT DEFAULT NULL, ADD shoes_sizes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFFE16FE4 FOREIGN KEY (tshirt_sizes_id) REFERENCES tshirt_sizes (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFF85C5DA FOREIGN KEY (shoes_sizes_id) REFERENCES shoes_sizes (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADFFE16FE4 ON product (tshirt_sizes_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADFF85C5DA ON product (shoes_sizes_id)');
    }
}
