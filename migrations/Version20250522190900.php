<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250522190900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Setup Dullahan';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE asset (id INT AUTO_INCREMENT NOT NULL, user_data_id INT NOT NULL, full_path VARCHAR(255) NOT NULL, directory VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, extension VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, weight INT DEFAULT NULL, hidden TINYINT(1) NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_2AF5A5C6FF8BF36 (user_data_id), INDEX path_search_idx (directory, name, extension), UNIQUE INDEX full_path_unique_idx (full_path), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE asset_pointer (id INT AUTO_INCREMENT NOT NULL, asset_id INT NOT NULL, entity_class VARCHAR(255) NOT NULL, entity_id INT NOT NULL, entity_column VARCHAR(255) NOT NULL, INDEX IDX_51EA29FE5DA1941 (asset_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE asset_thumbnail_pointer (asset_pointer_id INT NOT NULL, thumbnail_id INT NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_414783B1DD820F36 (asset_pointer_id), INDEX IDX_414783B1FDFF2E92 (thumbnail_id), PRIMARY KEY(asset_pointer_id, thumbnail_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE inherit_empty (id INT AUTO_INCREMENT NOT NULL, entity_class VARCHAR(255) NOT NULL, entity_id INT NOT NULL, entity_field VARCHAR(255) NOT NULL, INDEX entity_class_idx (entity_class), INDEX entity_id_idx (entity_id), INDEX entity_field_idx (entity_field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, data JSON NOT NULL, UNIQUE INDEX UNIQ_E545A0C55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE thumbnail (id INT AUTO_INCREMENT NOT NULL, asset_id INT NOT NULL, name VARCHAR(255) NOT NULL, weight INT NOT NULL, settings VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_C35726E65DA1941 (asset_id), INDEX duplicate_find_idx (settings, asset_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trace (id INT AUTO_INCREMENT NOT NULL, payload JSON DEFAULT NULL, endpoint VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) NOT NULL, response JSON NOT NULL, code INT NOT NULL, user_id INT DEFAULT NULL, created INT NOT NULL, trace JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, activation_token VARCHAR(64) DEFAULT NULL, activated TINYINT(1) DEFAULT 0 NOT NULL, created INT NOT NULL, when_activated INT DEFAULT NULL, new_email VARCHAR(255) DEFAULT NULL, email_verification_token VARCHAR(64) DEFAULT NULL, activation_token_exp INT DEFAULT NULL, email_verification_token_exp INT DEFAULT NULL, password_verification_token VARCHAR(64) DEFAULT NULL, password_verification_token_exp INT DEFAULT NULL, new_password VARCHAR(255) DEFAULT NULL, password_reset_verification_token VARCHAR(64) DEFAULT NULL, password_reset_verification_token_exp INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_data (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, deleted INT DEFAULT NULL, old_name VARCHAR(255) DEFAULT NULL, public_id VARCHAR(255) NOT NULL, file_limit_bytes INT DEFAULT 10000000 NOT NULL, UNIQUE INDEX UNIQ_D772BFAAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5C6FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asset_pointer ADD CONSTRAINT FK_51EA29FE5DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asset_thumbnail_pointer ADD CONSTRAINT FK_414783B1DD820F36 FOREIGN KEY (asset_pointer_id) REFERENCES asset_pointer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asset_thumbnail_pointer ADD CONSTRAINT FK_414783B1FDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES thumbnail (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE thumbnail ADD CONSTRAINT FK_C35726E65DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_data ADD CONSTRAINT FK_D772BFAAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5C6FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asset_pointer DROP FOREIGN KEY FK_51EA29FE5DA1941
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asset_thumbnail_pointer DROP FOREIGN KEY FK_414783B1DD820F36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asset_thumbnail_pointer DROP FOREIGN KEY FK_414783B1FDFF2E92
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE thumbnail DROP FOREIGN KEY FK_C35726E65DA1941
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_data DROP FOREIGN KEY FK_D772BFAAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE asset
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE asset_pointer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE asset_thumbnail_pointer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE inherit_empty
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE settings
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE thumbnail
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trace
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_data
        SQL);
    }
}
