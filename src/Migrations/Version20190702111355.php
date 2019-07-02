<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190702111355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE affiliate (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_affiliate (category_id INT NOT NULL, affiliate_id INT NOT NULL, INDEX IDX_9E1A77FF12469DE2 (category_id), INDEX IDX_9E1A77FF9F12C49A (affiliate_id), PRIMARY KEY(category_id, affiliate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, how_to_apply LONGTEXT DEFAULT NULL, token VARCHAR(255) NOT NULL, is_public TINYINT(1) NOT NULL, is_validated TINYINT(1) NOT NULL, email VARCHAR(255) DEFAULT NULL, expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FBD8E0F812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_affiliate ADD CONSTRAINT FK_9E1A77FF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_affiliate ADD CONSTRAINT FK_9E1A77FF9F12C49A FOREIGN KEY (affiliate_id) REFERENCES affiliate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_affiliate DROP FOREIGN KEY FK_9E1A77FF9F12C49A');
        $this->addSql('ALTER TABLE category_affiliate DROP FOREIGN KEY FK_9E1A77FF12469DE2');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F812469DE2');
        $this->addSql('DROP TABLE affiliate');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_affiliate');
        $this->addSql('DROP TABLE job');
    }
}
