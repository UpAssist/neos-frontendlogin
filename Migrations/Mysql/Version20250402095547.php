<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 *
 */
final class Version20250402095547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken DROP INDEX UNIQ_8F0B4ECE7D3656A4, ADD INDEX IDX_8F0B4ECE7D3656A4 (account)');
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken ADD createdat DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken DROP INDEX IDX_8F0B4ECE7D3656A4, ADD UNIQUE INDEX UNIQ_8F0B4ECE7D3656A4 (account)');
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken DROP createdat');
    }
}
