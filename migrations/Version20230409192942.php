<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230409192942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE field_form (id INT AUTO_INCREMENT NOT NULL, form_id INT NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_required TINYINT(1) NOT NULL, INDEX IDX_D8B2E19B5FF69B7D (form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buttons_form (id INT AUTO_INCREMENT NOT NULL, form_id INT NOT NULL, label VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL ,INDEX IDX_F45FFDFB5FF69B7D (form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_form (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL ,PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buttons_form ADD CONSTRAINT FK_F45FFDFB5FF69B7D FOREIGN KEY (form_id) REFERENCES cms_form (id)');
        $this->addSql('ALTER TABLE field_form ADD CONSTRAINT FK_D8B2E19B5FF69B7D FOREIGN KEY (form_id) REFERENCES cms_form (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field_form DROP FOREIGN KEY FK_D8B2E19B5FF69B7D');
        $this->addSql('ALTER TABLE buttons_form DROP FOREIGN KEY FK_F45FFDFB5FF69B7D');
        $this->addSql('DROP TABLE buttons_form');
        $this->addSql('DROP TABLE field_form');
        $this->addSql('DROP TABLE cms_form');
    }
}
