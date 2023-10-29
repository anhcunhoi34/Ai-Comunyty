<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707183507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preset ADD category_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\', ADD is_protected TINYINT(1) NOT NULL, ADD image LONGTEXT DEFAULT NULL, ADD color LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE preset ADD CONSTRAINT FK_2C5FE43212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_2C5FE43212469DE2 ON preset (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preset DROP FOREIGN KEY FK_2C5FE43212469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_2C5FE43212469DE2 ON preset');
        $this->addSql('ALTER TABLE preset DROP category_id, DROP is_protected, DROP image, DROP color');
    }
}
