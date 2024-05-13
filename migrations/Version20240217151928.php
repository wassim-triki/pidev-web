<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217151928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_group ADD sponsoring_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post_group ADD CONSTRAINT FK_FADBC82AAD184922 FOREIGN KEY (sponsoring_id) REFERENCES sponsoring (id)');
        $this->addSql('CREATE INDEX IDX_FADBC82AAD184922 ON post_group (sponsoring_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_group DROP FOREIGN KEY FK_FADBC82AAD184922');
        $this->addSql('DROP INDEX IDX_FADBC82AAD184922 ON post_group');
        $this->addSql('ALTER TABLE post_group DROP sponsoring_id');
    }
}
