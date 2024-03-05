<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217231939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, question_id_id INT NOT NULL, body VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DADD4A259D86650F (user_id_id), UNIQUE INDEX UNIQ_DADD4A254FAF8F53 (question_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6F7494E9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A259D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A254FAF8F53 FOREIGN KEY (question_id_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A259D86650F');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A254FAF8F53');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D86650F');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
    }
}
