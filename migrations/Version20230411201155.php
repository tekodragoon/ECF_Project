<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411201155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, reserved_table_id INT DEFAULT NULL, simple_user_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', service TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_42C84955E946A430 (reserved_table_id), UNIQUE INDEX UNIQ_42C84955ED27CD6E (simple_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simple_guest (id INT AUTO_INCREMENT NOT NULL, simple_user_id INT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, adult TINYINT(1) NOT NULL, allergies VARCHAR(255) DEFAULT NULL, INDEX IDX_1EA5707FED27CD6E (simple_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simple_user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_registered TINYINT(1) NOT NULL, allergies VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E946A430 FOREIGN KEY (reserved_table_id) REFERENCES `table` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ED27CD6E FOREIGN KEY (simple_user_id) REFERENCES simple_user (id)');
        $this->addSql('ALTER TABLE simple_guest ADD CONSTRAINT FK_1EA5707FED27CD6E FOREIGN KEY (simple_user_id) REFERENCES simple_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E946A430');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ED27CD6E');
        $this->addSql('ALTER TABLE simple_guest DROP FOREIGN KEY FK_1EA5707FED27CD6E');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE simple_guest');
        $this->addSql('DROP TABLE simple_user');
    }
}
