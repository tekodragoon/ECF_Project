<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311163620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE opening_days (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, open TINYINT(1) NOT NULL, noon_service TINYINT(1) NOT NULL, evening_service TINYINT(1) NOT NULL, day_of_week SMALLINT NOT NULL, INDEX IDX_D613A4DB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE opening_days ADD CONSTRAINT FK_D613A4DB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opening_days DROP FOREIGN KEY FK_D613A4DB1E7706E');
        $this->addSql('DROP TABLE opening_days');
    }
}
