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
        $this->addSql('CREATE TABLE field_form (id INT AUTO_INCREMENT NOT NULL, form_id INT NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, options JSON DEFAULT NULL, INDEX IDX_D8B2E19B5FF69B7D (form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_form (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE field_form ADD CONSTRAINT FK_D8B2E19B5FF69B7D FOREIGN KEY (form_id) REFERENCES registration_form (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field_form DROP FOREIGN KEY FK_D8B2E19B5FF69B7D');
        $this->addSql('DROP TABLE field_form');
        $this->addSql('DROP TABLE registration_form');
    }
}
