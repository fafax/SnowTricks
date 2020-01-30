<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191219195843 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE trick (id INT AUTO_INCREMENT NOT NULL, groups_id_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, text LONGTEXT NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_D8F0A91E2EE7F9F3 (groups_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E2EE7F9F3 FOREIGN KEY (groups_id_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE asset CHANGE trick_id_id trick_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE avatar_file avatar_file VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5CB46B9EE8');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB281BE2E');
        $this->addSql('DROP TABLE trick');
        $this->addSql('ALTER TABLE asset CHANGE trick_id_id trick_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE avatar_file avatar_file VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
