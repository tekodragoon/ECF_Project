<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126113315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guest (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, adult TINYINT(1) NOT NULL, allergy LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_ACB79A35A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guest ADD CONSTRAINT FK_ACB79A35A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guest DROP FOREIGN KEY FK_ACB79A35A76ED395');
        $this->addSql('DROP TABLE guest');
    }
}
