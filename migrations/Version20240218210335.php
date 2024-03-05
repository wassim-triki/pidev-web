<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218210335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD email_verification_token VARCHAR(180) DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL, ADD reset_token VARCHAR(255) DEFAULT NULL, DROP date_of_birth');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C4995C67 ON user (email_verification_token)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649C4995C67 ON user');
        $this->addSql('ALTER TABLE user ADD date_of_birth DATETIME NOT NULL, DROP email_verification_token, DROP is_verified, DROP reset_token');
    }
}
