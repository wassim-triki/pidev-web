<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221214147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A259D86650F');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A254FAF8F53');
        $this->addSql('ALTER TABLE avertissement DROP FOREIGN KEY FK_8C10BF26A6096BE1');
        $this->addSql('ALTER TABLE postcommentaire DROP FOREIGN KEY FK_27C39105B7F48F09');
        $this->addSql('ALTER TABLE post_group DROP FOREIGN KEY FK_FADBC82AA76ED395');
        $this->addSql('ALTER TABLE post_group DROP FOREIGN KEY FK_FADBC82AAD184922');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D86650F');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404C1CECC4C');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D82F9C941D');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D89B2CFB69');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D812469DE2');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE avertissement');
        $this->addSql('DROP TABLE market');
        $this->addSql('DROP TABLE postcommentaire');
        $this->addSql('DROP TABLE post_group');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE sponsoring');
        $this->addSql('DROP TABLE voucher');
        $this->addSql('DROP TABLE voucher_category');
        $this->addSql('ALTER TABLE user ADD is_enabled TINYINT(1) NOT NULL, DROP avertissements_count, DROP reputation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, question_id_id INT NOT NULL, body VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DADD4A259D86650F (user_id_id), UNIQUE INDEX UNIQ_DADD4A254FAF8F53 (question_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE avertissement (id INT AUTO_INCREMENT NOT NULL, f_id INT DEFAULT NULL, reported_username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nombre_reclamation INT DEFAULT NULL, confirmation TINYINT(1) DEFAULT NULL, screen_shot VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, raison VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8C10BF26A6096BE1 (f_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE market (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, region VARCHAR(60) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, city VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, zip_code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE postcommentaire (id INT AUTO_INCREMENT NOT NULL, postgroup_id INT NOT NULL, commentaire LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_27C39105B7F48F09 (postgroup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post_group (id INT AUTO_INCREMENT NOT NULL, sponsoring_id INT DEFAULT NULL, user_id INT DEFAULT NULL, contenu LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, INDEX IDX_FADBC82AAD184922 (sponsoring_id), INDEX IDX_FADBC82AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, body VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6F7494E9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, s_id INT DEFAULT NULL, subject VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, reported_username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type_reclamation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, screen_shot VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_CE606404C1CECC4C (s_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sponsoring (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, contrat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE voucher (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, user_won_id INT NOT NULL, market_related_id INT NOT NULL, code VARCHAR(60) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, expiration DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', value DOUBLE PRECISION NOT NULL, usage_limit INT NOT NULL, type VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_valid TINYINT(1) NOT NULL, is_given_to_user TINYINT(1) NOT NULL, INDEX IDX_1392A5D89B2CFB69 (user_won_id), INDEX IDX_1392A5D82F9C941D (market_related_id), INDEX IDX_1392A5D812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE voucher_category (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, discription VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A259D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A254FAF8F53 FOREIGN KEY (question_id_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE avertissement ADD CONSTRAINT FK_8C10BF26A6096BE1 FOREIGN KEY (f_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postcommentaire ADD CONSTRAINT FK_27C39105B7F48F09 FOREIGN KEY (postgroup_id) REFERENCES post_group (id)');
        $this->addSql('ALTER TABLE post_group ADD CONSTRAINT FK_FADBC82AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_group ADD CONSTRAINT FK_FADBC82AAD184922 FOREIGN KEY (sponsoring_id) REFERENCES sponsoring (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404C1CECC4C FOREIGN KEY (s_id) REFERENCES avertissement (id)');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D82F9C941D FOREIGN KEY (market_related_id) REFERENCES market (id)');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D89B2CFB69 FOREIGN KEY (user_won_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D812469DE2 FOREIGN KEY (category_id) REFERENCES voucher_category (id)');
        $this->addSql('ALTER TABLE user ADD avertissements_count INT NOT NULL, ADD reputation INT DEFAULT NULL, DROP is_enabled');
    }
}
