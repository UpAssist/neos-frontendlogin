<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320203220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE upassist_neos_frontendlogin_domain_model_pas_867b9_account_join');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE upassist_neos_frontendlogin_domain_model_pas_867b9_account_join (frontendlogin_passwordresettoken VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, flow_security_account VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_B6996927AD7770F (frontendlogin_passwordresettoken), UNIQUE INDEX UNIQ_B699692758842EFC (flow_security_account), PRIMARY KEY(frontendlogin_passwordresettoken, flow_security_account)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_pas_867b9_account_join ADD CONSTRAINT FK_B699692758842EFC FOREIGN KEY (flow_security_account) REFERENCES neos_flow_security_account (persistence_object_identifier) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE upassist_neos_frontendlogin_domain_model_pas_867b9_account_join ADD CONSTRAINT FK_B6996927AD7770F FOREIGN KEY (frontendlogin_passwordresettoken) REFERENCES upassist_neos_frontendlogin_domain_model_passwordresettoken (persistence_object_identifier) ON DELETE CASCADE');
    }
}
