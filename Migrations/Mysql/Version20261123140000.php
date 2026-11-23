<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Delete password reset tokens together with their account, so that deleting a
 * user with an open reset token no longer fails on the foreign key.
 */
final class Version20261123140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cascade deletes from neos_flow_security_account to the password reset tokens';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken DROP FOREIGN KEY FK_8F0B4ECE7D3656A4');
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken ADD CONSTRAINT FK_8F0B4ECE7D3656A4 FOREIGN KEY (account) REFERENCES neos_flow_security_account (persistence_object_identifier) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken DROP FOREIGN KEY FK_8F0B4ECE7D3656A4');
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_passwordresettoken ADD CONSTRAINT FK_8F0B4ECE7D3656A4 FOREIGN KEY (account) REFERENCES neos_flow_security_account (persistence_object_identifier)');
    }
}
