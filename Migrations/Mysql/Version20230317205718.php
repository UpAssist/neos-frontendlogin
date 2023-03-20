<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317205718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken (persistence_object_identifier VARCHAR(40) NOT NULL, account VARCHAR(40) DEFAULT NULL, token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8F0B4ECE7D3656A4 (account), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken ADD CONSTRAINT FK_8F0B4ECE7D3656A4 FOREIGN KEY (account) REFERENCES neos_flow_security_account (persistence_object_identifier)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken');
    }
}
