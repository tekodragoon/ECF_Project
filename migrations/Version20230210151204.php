<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210151204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE composition (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C7F4347CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE composition ADD CONSTRAINT FK_C7F4347CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu DROP composition');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE composition DROP FOREIGN KEY FK_C7F4347CCD7E912');
        $this->addSql('DROP TABLE composition');
        $this->addSql('ALTER TABLE menu ADD composition LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\'');
    }
}
