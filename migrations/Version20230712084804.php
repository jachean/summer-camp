<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712084804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team CHANGE standings_id standings_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F7F97F032 FOREIGN KEY (standings_id) REFERENCES standings (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F7F97F032 ON team (standings_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F7F97F032');
        $this->addSql('DROP INDEX IDX_C4E0A61F7F97F032 ON team');
        $this->addSql('ALTER TABLE team CHANGE standings_id standings_id INT NOT NULL');
    }
}
