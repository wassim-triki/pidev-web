<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305191712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, question_id_id INT NOT NULL, body VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DADD4A259D86650F (user_id_id), UNIQUE INDEX UNIQ_DADD4A254FAF8F53 (question_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_group (id INT AUTO_INCREMENT NOT NULL, sponsoring_id INT DEFAULT NULL, user_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date DATE NOT NULL, INDEX IDX_FADBC82AAD184922 (sponsoring_id), INDEX IDX_FADBC82AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postcommentaire (id INT AUTO_INCREMENT NOT NULL, postgroup_id INT NOT NULL, user_id INT DEFAULT NULL, commentaire LONGTEXT NOT NULL, likes INT NOT NULL, liked_by LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_27C39105B7F48F09 (postgroup_id), INDEX IDX_27C39105A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6F7494E9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsoring (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, image VARCHAR(255) NOT NULL, contrat VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A259D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A254FAF8F53 FOREIGN KEY (question_id_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE post_group ADD CONSTRAINT FK_FADBC82AAD184922 FOREIGN KEY (sponsoring_id) REFERENCES sponsoring (id)');
        $this->addSql('ALTER TABLE post_group ADD CONSTRAINT FK_FADBC82AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postcommentaire ADD CONSTRAINT FK_27C39105B7F48F09 FOREIGN KEY (postgroup_id) REFERENCES post_group (id)');
        $this->addSql('ALTER TABLE postcommentaire ADD CONSTRAINT FK_27C39105A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avertissement DROP nombre_reclamation');
        $this->addSql('ALTER TABLE user ADD is_enabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A259D86650F');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A254FAF8F53');
        $this->addSql('ALTER TABLE post_group DROP FOREIGN KEY FK_FADBC82AAD184922');
        $this->addSql('ALTER TABLE post_group DROP FOREIGN KEY FK_FADBC82AA76ED395');
        $this->addSql('ALTER TABLE postcommentaire DROP FOREIGN KEY FK_27C39105B7F48F09');
        $this->addSql('ALTER TABLE postcommentaire DROP FOREIGN KEY FK_27C39105A76ED395');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D86650F');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE post_group');
        $this->addSql('DROP TABLE postcommentaire');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE sponsoring');
        $this->addSql('ALTER TABLE avertissement ADD nombre_reclamation INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP is_enabled');
    }
}
