<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727050314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan ADD billing_cycle VARCHAR(255) DEFAULT NULL, ADD status SMALLINT NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD price INT NOT NULL, ADD token_limit INT DEFAULT NULL, ADD image_limit INT DEFAULT NULL, ADD audio_limit INT DEFAULT NULL, ADD superiority SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan DROP billing_cycle, DROP status, DROP title, DROP description, DROP price, DROP token_limit, DROP image_limit, DROP audio_limit, DROP superiority');
    }
}
