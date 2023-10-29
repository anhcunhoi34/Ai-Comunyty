<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230914124739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `option` RENAME INDEX uniq_5a8600b08a90aba9 TO UNIQ_5A8600B04E645A7E');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A3C664D3DB7D3959F75D7B0 ON subscription (payment_gateway, external_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_A3C664D3DB7D3959F75D7B0 ON subscription');
        $this->addSql('ALTER TABLE `option` RENAME INDEX uniq_5a8600b04e645a7e TO UNIQ_5A8600B08A90ABA9');
    }
}
