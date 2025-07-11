<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250711113824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE _audit_favorite (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, object_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, discriminator VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, transaction_hash VARCHAR(40) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_fqdn VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_firewall VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(45) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX type_071bf4e6dc218ff9d5a8e5d10da98269_idx (type), INDEX object_id_071bf4e6dc218ff9d5a8e5d10da98269_idx (object_id), INDEX discriminator_071bf4e6dc218ff9d5a8e5d10da98269_idx (discriminator), INDEX transaction_hash_071bf4e6dc218ff9d5a8e5d10da98269_idx (transaction_hash), INDEX blame_id_071bf4e6dc218ff9d5a8e5d10da98269_idx (blame_id), INDEX created_at_071bf4e6dc218ff9d5a8e5d10da98269_idx (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE _audit_issue_type (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, object_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, discriminator VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, transaction_hash VARCHAR(40) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_fqdn VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_firewall VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(45) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX type_5e66592c5e302c99d2b92a5ec8c46929_idx (type), INDEX object_id_5e66592c5e302c99d2b92a5ec8c46929_idx (object_id), INDEX discriminator_5e66592c5e302c99d2b92a5ec8c46929_idx (discriminator), INDEX transaction_hash_5e66592c5e302c99d2b92a5ec8c46929_idx (transaction_hash), INDEX blame_id_5e66592c5e302c99d2b92a5ec8c46929_idx (blame_id), INDEX created_at_5e66592c5e302c99d2b92a5ec8c46929_idx (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE _audit_priority (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, object_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, discriminator VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, transaction_hash VARCHAR(40) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_fqdn VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_firewall VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(45) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX type_38492a9509a6cc3eb9dafddb60c1dfad_idx (type), INDEX object_id_38492a9509a6cc3eb9dafddb60c1dfad_idx (object_id), INDEX discriminator_38492a9509a6cc3eb9dafddb60c1dfad_idx (discriminator), INDEX transaction_hash_38492a9509a6cc3eb9dafddb60c1dfad_idx (transaction_hash), INDEX blame_id_38492a9509a6cc3eb9dafddb60c1dfad_idx (blame_id), INDEX created_at_38492a9509a6cc3eb9dafddb60c1dfad_idx (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE _audit_project (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, object_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, discriminator VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, transaction_hash VARCHAR(40) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_fqdn VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_firewall VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(45) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX type_eb2260391b8ca42f7a1f7656766ef866_idx (type), INDEX object_id_eb2260391b8ca42f7a1f7656766ef866_idx (object_id), INDEX discriminator_eb2260391b8ca42f7a1f7656766ef866_idx (discriminator), INDEX transaction_hash_eb2260391b8ca42f7a1f7656766ef866_idx (transaction_hash), INDEX blame_id_eb2260391b8ca42f7a1f7656766ef866_idx (blame_id), INDEX created_at_eb2260391b8ca42f7a1f7656766ef866_idx (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE _audit_user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, object_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, discriminator VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, transaction_hash VARCHAR(40) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_fqdn VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, blame_user_firewall VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(45) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX type_614a040c6a2b6b682bedde4b8839da12_idx (type), INDEX object_id_614a040c6a2b6b682bedde4b8839da12_idx (object_id), INDEX discriminator_614a040c6a2b6b682bedde4b8839da12_idx (discriminator), INDEX transaction_hash_614a040c6a2b6b682bedde4b8839da12_idx (transaction_hash), INDEX blame_id_614a040c6a2b6b682bedde4b8839da12_idx (blame_id), INDEX created_at_614a040c6a2b6b682bedde4b8839da12_idx (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE _audit_favorite');
        $this->addSql('DROP TABLE _audit_issue_type');
        $this->addSql('DROP TABLE _audit_priority');
        $this->addSql('DROP TABLE _audit_project');
        $this->addSql('DROP TABLE _audit_user');
    }
}
