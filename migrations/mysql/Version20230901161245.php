<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230901161245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6A27CCD');
        $this->addSql('DROP INDEX UNIQ_8D93D649F6A27CCD ON user');
        $this->addSql('ALTER TABLE user CHANGE activeSubscription_id active_subscription_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499A208144 FOREIGN KEY (active_subscription_id) REFERENCES subscription (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499A208144 ON user (active_subscription_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499A208144');
        $this->addSql('DROP INDEX UNIQ_8D93D6499A208144 ON user');
        $this->addSql('ALTER TABLE user CHANGE active_subscription_id activeSubscription_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6A27CCD FOREIGN KEY (activeSubscription_id) REFERENCES subscription (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F6A27CCD ON user (activeSubscription_id)');
        $this->addSql('ALTER TABLE subscription CHANGE status status VARCHAR(30) NOT NULL');
    }
}
