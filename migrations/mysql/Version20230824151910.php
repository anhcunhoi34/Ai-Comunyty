<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824151910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan ADD token_credit INT DEFAULT NULL, ADD image_credit INT DEFAULT NULL, ADD audio_credit INT DEFAULT NULL, DROP token_limit, DROP image_limit, DROP audio_limit');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan ADD token_limit INT DEFAULT NULL, ADD image_limit INT DEFAULT NULL, ADD audio_limit INT DEFAULT NULL, DROP token_credit, DROP image_credit, DROP audio_credit');
    }
}
