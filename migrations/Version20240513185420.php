<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513185420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Change the column type to JSON
        $this->addSql('ALTER TABLE postcommentaire MODIFY likedBy JSON NOT NULL');

        // Update existing data
        $conn = $this->connection;
        $stmt = $conn->executeQuery('SELECT id, likedBy FROM postcommentaire');
        while ($row = $stmt->fetchAssociative()) {
            $likedBy = @unserialize($row['likedBy']);
            if ($likedBy === false && $row['likedBy'] !== 'b:0;') {
                $likedBy = []; // Default to empty array if unserialization fails
            }
            $likedByJson = json_encode($likedBy);
            $conn->executeStatement('UPDATE postcommentaire SET likedBy = ? WHERE id = ?', [$likedByJson, $row['id']]);
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post INT NOT NULL, id_u INT NOT NULL, text VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_comment_post (post), INDEX idad (id_u), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE verification_code (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, code VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, expiry_date DATETIME DEFAULT NULL, INDEX fk_verification_code_user (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_comment_post FOREIGN KEY (post) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT idad FOREIGN KEY (id_u) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE verification_code ADD CONSTRAINT fk_verification_code_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE verification_code ADD CONSTRAINT verification_code_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A259D86650F');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A254FAF8F53');
        $this->addSql('DROP INDEX IDX_DADD4A259D86650F ON answer');
        $this->addSql('DROP INDEX UNIQ_DADD4A254FAF8F53 ON answer');
        $this->addSql('ALTER TABLE answer CHANGE user_id_id userId INT DEFAULT NULL, CHANGE question_id_id questionId INT NOT NULL, CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A259D86650F FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A254FAF8F53 FOREIGN KEY (questionId) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A259D86650F ON answer (userId)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DADD4A254FAF8F53 ON answer (questionId)');
        $this->addSql('ALTER TABLE postcommentaire DROP FOREIGN KEY FK_27C39105B7F48F09');
        $this->addSql('ALTER TABLE postcommentaire DROP FOREIGN KEY FK_27C39105A76ED395');
        $this->addSql('ALTER TABLE postcommentaire CHANGE likes likes INT DEFAULT 0 NOT NULL, CHANGE liked_by liked_by LONGTEXT DEFAULT \'[]\' NOT NULL');
        $this->addSql('ALTER TABLE postcommentaire ADD CONSTRAINT FK_27C39105B7F48F09 FOREIGN KEY (postgroup_id) REFERENCES post_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postcommentaire ADD CONSTRAINT FK_27C39105A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D86650F');
        $this->addSql('DROP INDEX IDX_B6F7494E9D86650F ON question');
        $this->addSql('ALTER TABLE question CHANGE user_id_id userId INT NOT NULL, CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9D86650F FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E9D86650F ON question (userId)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE is_enabled is_enabled TINYINT(1) DEFAULT 1 NOT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT 0 NOT NULL, CHANGE avertissements_count avertissements_count INT DEFAULT NULL');
    }
}
