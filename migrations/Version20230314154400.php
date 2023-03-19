<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314154400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, added_on DATE NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories CHANGE author author VARCHAR(255) NOT NULL, CHANGE updated updated VARCHAR(255) NOT NULL, CHANGE created_on created_on DATE NOT NULL, CHANGE updated_on updated_on DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE categories CHANGE author author VARCHAR(255) DEFAULT NULL, CHANGE updated updated VARCHAR(255) DEFAULT NULL, CHANGE created_on created_on DATE DEFAULT NULL, CHANGE updated_on updated_on DATE DEFAULT NULL');
    }
}
