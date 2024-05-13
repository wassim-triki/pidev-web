<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215230731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postcommentaire ADD postgroup_id INT NOT NULL');
        $this->addSql('ALTER TABLE postcommentaire ADD CONSTRAINT FK_27C39105B7F48F09 FOREIGN KEY (postgroup_id) REFERENCES post_group (id)');
        $this->addSql('CREATE INDEX IDX_27C39105B7F48F09 ON postcommentaire (postgroup_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postcommentaire DROP FOREIGN KEY FK_27C39105B7F48F09');
        $this->addSql('DROP INDEX IDX_27C39105B7F48F09 ON postcommentaire');
        $this->addSql('ALTER TABLE postcommentaire DROP postgroup_id');
    }
}
