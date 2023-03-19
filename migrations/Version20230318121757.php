<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230318121757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, added_on DATE NOT NULL, UNIQUE INDEX UNIQ_CAC89EACE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, updated VARCHAR(255) NOT NULL, updated_on DATE NOT NULL, url VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, short_description LONGTEXT NOT NULL, article_picture VARCHAR(255) NOT NULL, other_file VARCHAR(255) NOT NULL, tags VARCHAR(255) NOT NULL, created_on DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_categories (articles_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_DE004A0E1EBAF6CC (articles_id), INDEX IDX_DE004A0EA21214B7 (categories_id), PRIMARY KEY(articles_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_sub_category (articles_id INT NOT NULL, sub_category_id INT NOT NULL, INDEX IDX_86F293611EBAF6CC (articles_id), INDEX IDX_86F29361F7BFE87C (sub_category_id), PRIMARY KEY(articles_id, sub_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, updated VARCHAR(255) NOT NULL, created_on DATE NOT NULL, updated_on DATE NOT NULL, slug VARCHAR(255) NOT NULL, category_picture VARCHAR(255) NOT NULL, short_description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_category (id INT AUTO_INCREMENT NOT NULL, categories_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, short_description LONGTEXT NOT NULL, sub_category_picture VARCHAR(255) NOT NULL, INDEX IDX_BCE3F798A21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles_categories ADD CONSTRAINT FK_DE004A0E1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_categories ADD CONSTRAINT FK_DE004A0EA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_sub_category ADD CONSTRAINT FK_86F293611EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_sub_category ADD CONSTRAINT FK_86F29361F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F798A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles_categories DROP FOREIGN KEY FK_DE004A0E1EBAF6CC');
        $this->addSql('ALTER TABLE articles_categories DROP FOREIGN KEY FK_DE004A0EA21214B7');
        $this->addSql('ALTER TABLE articles_sub_category DROP FOREIGN KEY FK_86F293611EBAF6CC');
        $this->addSql('ALTER TABLE articles_sub_category DROP FOREIGN KEY FK_86F29361F7BFE87C');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F798A21214B7');
        $this->addSql('DROP TABLE accounts IF EXISTS ');
        $this->addSql('DROP TABLE articles  IF EXISTS');
        $this->addSql('DROP TABLE articles_categories  IF EXISTS');
        $this->addSql('DROP TABLE articles_sub_category  IF EXISTS');
        $this->addSql('DROP TABLE categories  IF EXISTS');
        $this->addSql('DROP TABLE sub_category  IF EXISTS');
        $this->addSql('DROP TABLE messenger_messages  IF EXISTS');
    }
}
