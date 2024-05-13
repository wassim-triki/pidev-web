<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218143229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_group ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post_group ADD CONSTRAINT FK_FADBC82AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FADBC82AA76ED395 ON post_group (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_group DROP FOREIGN KEY FK_FADBC82AA76ED395');
        $this->addSql('DROP INDEX IDX_FADBC82AA76ED395 ON post_group');
        $this->addSql('ALTER TABLE post_group DROP user_id');
    }
}
