<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224144913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery_images ADD recipe_id INT DEFAULT NULL, ADD path VARCHAR(255) NOT NULL, ADD visible TINYINT(1) NOT NULL, DROP information');
        $this->addSql('ALTER TABLE gallery_images ADD CONSTRAINT FK_429C52C859D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_429C52C859D8A214 ON gallery_images (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery_images DROP FOREIGN KEY FK_429C52C859D8A214');
        $this->addSql('DROP INDEX UNIQ_429C52C859D8A214 ON gallery_images');
        $this->addSql('ALTER TABLE gallery_images ADD information LONGTEXT NOT NULL, DROP recipe_id, DROP path, DROP visible');
    }
}
