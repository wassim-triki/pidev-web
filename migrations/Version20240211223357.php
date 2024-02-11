<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211223357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, magasin_name VARCHAR(50) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vocher (id INT AUTO_INCREMENT NOT NULL, boutique_id INT NOT NULL, user_won_id INT DEFAULT NULL, category_id INT NOT NULL, code VARCHAR(60) NOT NULL, expiration_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', value DOUBLE PRECISION NOT NULL, usage_limite INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_BE0DE264AB677BE6 (boutique_id), INDEX IDX_BE0DE2649B2CFB69 (user_won_id), INDEX IDX_BE0DE26412469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vocher_category (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, discription VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vocher ADD CONSTRAINT FK_BE0DE264AB677BE6 FOREIGN KEY (boutique_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE vocher ADD CONSTRAINT FK_BE0DE2649B2CFB69 FOREIGN KEY (user_won_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vocher ADD CONSTRAINT FK_BE0DE26412469DE2 FOREIGN KEY (category_id) REFERENCES vocher_category (id)');
        $this->addSql('ALTER TABLE user ADD date_of_birth DATETIME NOT NULL, ADD reputation INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vocher DROP FOREIGN KEY FK_BE0DE264AB677BE6');
        $this->addSql('ALTER TABLE vocher DROP FOREIGN KEY FK_BE0DE2649B2CFB69');
        $this->addSql('ALTER TABLE vocher DROP FOREIGN KEY FK_BE0DE26412469DE2');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE vocher');
        $this->addSql('DROP TABLE vocher_category');
        $this->addSql('ALTER TABLE user DROP date_of_birth, DROP reputation');
    }
}
