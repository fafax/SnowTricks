<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191002051602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_D8F0A91E2F68B530 ON trick');
        $this->addSql('ALTER TABLE trick ADD groups_id_id INT DEFAULT NULL, DROP group_id_id, CHANGE update_date update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E2EE7F9F3 FOREIGN KEY (groups_id_id) REFERENCES groups (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E2EE7F9F3 ON trick (groups_id_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E2EE7F9F3');
        $this->addSql('DROP INDEX IDX_D8F0A91E2EE7F9F3 ON trick');
        $this->addSql('ALTER TABLE trick ADD group_id_id INT DEFAULT NULL, DROP groups_id_id, CHANGE update_date update_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('CREATE INDEX IDX_D8F0A91E2F68B530 ON trick (group_id_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
