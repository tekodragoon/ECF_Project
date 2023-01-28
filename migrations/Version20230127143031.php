<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230127143031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergy (id INT AUTO_INCREMENT NOT NULL, guest_id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CBB142B59A4AA658 (guest_id), INDEX IDX_CBB142B5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergy ADD CONSTRAINT FK_CBB142B59A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE allergy ADD CONSTRAINT FK_CBB142B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE guest DROP allergy');
        $this->addSql('ALTER TABLE user DROP allergies');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allergy DROP FOREIGN KEY FK_CBB142B59A4AA658');
        $this->addSql('ALTER TABLE allergy DROP FOREIGN KEY FK_CBB142B5A76ED395');
        $this->addSql('DROP TABLE allergy');
        $this->addSql('ALTER TABLE guest ADD allergy LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE user ADD allergies LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }
}
