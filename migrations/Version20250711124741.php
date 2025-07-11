<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250711124741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE _log_entry (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, object_id VARCHAR(255) NOT NULL, context JSON DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_46C3D730A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE _log_entry ADD CONSTRAINT FK_46C3D730A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE _log_entry DROP FOREIGN KEY FK_46C3D730A76ED395');
        $this->addSql('DROP TABLE _log_entry');
    }
}
