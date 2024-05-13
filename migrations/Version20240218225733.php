<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218225733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avertissement (id INT AUTO_INCREMENT NOT NULL, f_id INT DEFAULT NULL, reported_username VARCHAR(255) DEFAULT NULL, nombre_reclamation INT DEFAULT NULL, confirmation TINYINT(1) DEFAULT NULL, screen_shot VARCHAR(255) NOT NULL, raison VARCHAR(255) NOT NULL, INDEX IDX_8C10BF26A6096BE1 (f_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, place VARCHAR(255) NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, s_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, reported_username VARCHAR(255) NOT NULL, type_reclamation VARCHAR(255) NOT NULL, screen_shot VARCHAR(255) NOT NULL, INDEX IDX_CE606404C1CECC4C (s_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avertissement ADD CONSTRAINT FK_8C10BF26A6096BE1 FOREIGN KEY (f_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404C1CECC4C FOREIGN KEY (s_id) REFERENCES avertissement (id)');
        $this->addSql('ALTER TABLE user ADD email_verification_token VARCHAR(180) DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL, ADD reset_token VARCHAR(255) DEFAULT NULL, ADD avertissements_count INT NOT NULL, DROP date_of_birth');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C4995C67 ON user (email_verification_token)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avertissement DROP FOREIGN KEY FK_8C10BF26A6096BE1');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404C1CECC4C');
        $this->addSql('DROP TABLE avertissement');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP INDEX UNIQ_8D93D649C4995C67 ON user');
        $this->addSql('ALTER TABLE user ADD date_of_birth DATETIME NOT NULL, DROP email_verification_token, DROP is_verified, DROP reset_token, DROP avertissements_count');
    }
}
