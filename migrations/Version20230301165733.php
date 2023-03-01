<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301165733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergy (id INT AUTO_INCREMENT NOT NULL, guest_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CBB142B59A4AA658 (guest_id), INDEX IDX_CBB142B5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formula (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_67315881CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_image (id INT AUTO_INCREMENT NOT NULL, recipe_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_21A0D47C59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guest (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, adult TINYINT(1) NOT NULL, INDEX IDX_ACB79A35A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mailing (id INT AUTO_INCREMENT NOT NULL, usermail VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3ED9315EF75BD439 (usermail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, price SMALLINT NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price SMALLINT NOT NULL, INDEX IDX_DA88B13712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, list_order SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, lastname VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergy ADD CONSTRAINT FK_CBB142B59A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE allergy ADD CONSTRAINT FK_CBB142B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE formula ADD CONSTRAINT FK_67315881CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE gallery_image ADD CONSTRAINT FK_21A0D47C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE guest ADD CONSTRAINT FK_ACB79A35A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13712469DE2 FOREIGN KEY (category_id) REFERENCES recipe_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allergy DROP FOREIGN KEY FK_CBB142B59A4AA658');
        $this->addSql('ALTER TABLE allergy DROP FOREIGN KEY FK_CBB142B5A76ED395');
        $this->addSql('ALTER TABLE formula DROP FOREIGN KEY FK_67315881CCD7E912');
        $this->addSql('ALTER TABLE gallery_image DROP FOREIGN KEY FK_21A0D47C59D8A214');
        $this->addSql('ALTER TABLE guest DROP FOREIGN KEY FK_ACB79A35A76ED395');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13712469DE2');
        $this->addSql('DROP TABLE allergy');
        $this->addSql('DROP TABLE formula');
        $this->addSql('DROP TABLE gallery_image');
        $this->addSql('DROP TABLE guest');
        $this->addSql('DROP TABLE mailing');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_category');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
