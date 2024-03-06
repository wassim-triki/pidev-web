<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212002942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE market (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, date_of_birth DATETIME NOT NULL, reputation INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voucher (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, user_won_id INT NOT NULL, market_related_id INT NOT NULL, code VARCHAR(60) NOT NULL, expiration DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', value DOUBLE PRECISION NOT NULL, usage_limit INT NOT NULL, type VARCHAR(50) DEFAULT NULL, INDEX IDX_1392A5D812469DE2 (category_id), INDEX IDX_1392A5D89B2CFB69 (user_won_id), INDEX IDX_1392A5D82F9C941D (market_related_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voucher_category (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, discription VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D812469DE2 FOREIGN KEY (category_id) REFERENCES voucher_category (id)');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D89B2CFB69 FOREIGN KEY (user_won_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D82F9C941D FOREIGN KEY (market_related_id) REFERENCES market (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D812469DE2');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D89B2CFB69');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D82F9C941D');
        $this->addSql('DROP TABLE market');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE voucher');
        $this->addSql('DROP TABLE voucher_category');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
