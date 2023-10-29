<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230915201031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan ADD token_credit_count INT DEFAULT NULL, ADD image_credit_count INT DEFAULT NULL, ADD audio_credit_count INT DEFAULT NULL, DROP token_credit, DROP image_credit, DROP audio_credit');
        $this->addSql('ALTER TABLE subscription ADD token_usage_count INT DEFAULT NULL, ADD image_usage_count INT DEFAULT NULL, ADD audio_usage_count INT DEFAULT NULL, DROP token_credit, DROP image_credit, DROP audio_credit, CHANGE renew_credits_at reset_credits_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription ADD token_credit INT DEFAULT NULL, ADD image_credit INT DEFAULT NULL, ADD audio_credit INT DEFAULT NULL, DROP token_usage_count, DROP image_usage_count, DROP audio_usage_count, CHANGE reset_credits_at renew_credits_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE plan ADD token_credit INT DEFAULT NULL, ADD image_credit INT DEFAULT NULL, ADD audio_credit INT DEFAULT NULL, DROP token_credit_count, DROP image_credit_count, DROP audio_credit_count');
    }
}
