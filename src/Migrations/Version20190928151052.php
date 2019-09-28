<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190928151052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset ADD trick_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5CB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5CB46B9EE8 ON asset (trick_id_id)');
        $this->addSql('ALTER TABLE comment ADD user_id_id INT NOT NULL, ADD trick_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_9474526C9D86650F ON comment (user_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526CB46B9EE8 ON comment (trick_id_id)');
        $this->addSql('ALTER TABLE trick ADD group_id_id INT DEFAULT NULL, CHANGE update_date update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E2F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E2F68B530 ON trick (group_id_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5CB46B9EE8');
        $this->addSql('DROP INDEX IDX_2AF5A5CB46B9EE8 ON asset');
        $this->addSql('ALTER TABLE asset DROP trick_id_id');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9D86650F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB46B9EE8');
        $this->addSql('DROP INDEX IDX_9474526C9D86650F ON comment');
        $this->addSql('DROP INDEX IDX_9474526CB46B9EE8 ON comment');
        $this->addSql('ALTER TABLE comment DROP user_id_id, DROP trick_id_id');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E2F68B530');
        $this->addSql('DROP INDEX IDX_D8F0A91E2F68B530 ON trick');
        $this->addSql('ALTER TABLE trick DROP group_id_id, CHANGE update_date update_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
