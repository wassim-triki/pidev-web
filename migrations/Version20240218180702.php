<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218180702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE market ADD region VARCHAR(60) NOT NULL, ADD city VARCHAR(50) NOT NULL, ADD zip_code INT NOT NULL');
        $this->addSql('ALTER TABLE voucher ADD is_valid TINYINT(1) NOT NULL, ADD is_given_to_user TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE market DROP region, DROP city, DROP zip_code');
        $this->addSql('ALTER TABLE voucher DROP is_valid, DROP is_given_to_user');
    }
}
