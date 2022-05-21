<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220521222847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shoes_sizes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tshirt_sizes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE product_sizes');
        $this->addSql('ALTER TABLE product ADD tshirt_sizes_id INT DEFAULT NULL, ADD shoes_sizes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFFE16FE4 FOREIGN KEY (tshirt_sizes_id) REFERENCES tshirt_sizes (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFF85C5DA FOREIGN KEY (shoes_sizes_id) REFERENCES shoes_sizes (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADFFE16FE4 ON product (tshirt_sizes_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADFF85C5DA ON product (shoes_sizes_id)');
        $this->addSql('ALTER TABLE sizes ADD CONSTRAINT FK_B69E876912469DE2 FOREIGN KEY (category_id) REFERENCES category_prodcut (id)');
        $this->addSql('CREATE INDEX IDX_B69E876912469DE2 ON sizes (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFF85C5DA');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFFE16FE4');
        $this->addSql('CREATE TABLE product_sizes (product_id INT NOT NULL, sizes_id INT NOT NULL, INDEX IDX_17C2FC35423285E6 (sizes_id), INDEX IDX_17C2FC354584665A (product_id), PRIMARY KEY(product_id, sizes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_sizes ADD CONSTRAINT FK_17C2FC354584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_sizes ADD CONSTRAINT FK_17C2FC35423285E6 FOREIGN KEY (sizes_id) REFERENCES sizes (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE shoes_sizes');
        $this->addSql('DROP TABLE tshirt_sizes');
        $this->addSql('DROP INDEX IDX_D34A04ADFFE16FE4 ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADFF85C5DA ON product');
        $this->addSql('ALTER TABLE product DROP tshirt_sizes_id, DROP shoes_sizes_id');
        $this->addSql('ALTER TABLE sizes DROP FOREIGN KEY FK_B69E876912469DE2');
        $this->addSql('DROP INDEX IDX_B69E876912469DE2 ON sizes');
    }
}
