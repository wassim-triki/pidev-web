<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216213450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avertissement ADD f_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avertissement ADD CONSTRAINT FK_8C10BF26A6096BE1 FOREIGN KEY (f_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8C10BF26A6096BE1 ON avertissement (f_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avertissement DROP FOREIGN KEY FK_8C10BF26A6096BE1');
        $this->addSql('DROP INDEX IDX_8C10BF26A6096BE1 ON avertissement');
        $this->addSql('ALTER TABLE avertissement DROP f_id');
    }
}
