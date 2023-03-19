<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314154742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories CHANGE author author VARCHAR(255) NOT NULL, CHANGE updated updated VARCHAR(255) NOT NULL, CHANGE created_on created_on DATE NOT NULL, CHANGE updated_on updated_on DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories CHANGE author author VARCHAR(255) DEFAULT NULL, CHANGE updated updated VARCHAR(255) DEFAULT NULL, CHANGE created_on created_on DATE DEFAULT NULL, CHANGE updated_on updated_on DATE DEFAULT NULL');
    }
}
