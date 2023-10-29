<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824152047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', plan_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, customer_external_id VARCHAR(255) DEFAULT NULL, price_external_id VARCHAR(255) DEFAULT NULL, product_external_id VARCHAR(255) DEFAULT NULL, token_credit INT DEFAULT NULL, image_credit INT DEFAULT NULL, audio_credit INT DEFAULT NULL, INDEX IDX_A3C664D3A76ED395 (user_id), INDEX IDX_A3C664D3E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE user ADD activeSubscription_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6A27CCD FOREIGN KEY (activeSubscription_id) REFERENCES subscription (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F6A27CCD ON user (activeSubscription_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6A27CCD');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3E899029B');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP INDEX UNIQ_8D93D649F6A27CCD ON user');
        $this->addSql('ALTER TABLE user DROP activeSubscription_id');
    }
}
