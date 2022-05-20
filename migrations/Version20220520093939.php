<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520093939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_key_words (product_id INT NOT NULL, key_words_id INT NOT NULL, INDEX IDX_FDE3D5CE4584665A (product_id), INDEX IDX_FDE3D5CEB598DE74 (key_words_id), PRIMARY KEY(product_id, key_words_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_key_words ADD CONSTRAINT FK_FDE3D5CE4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_key_words ADD CONSTRAINT FK_FDE3D5CEB598DE74 FOREIGN KEY (key_words_id) REFERENCES key_words (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_key_words');
    }
}
