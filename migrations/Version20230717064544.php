<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717064544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matches ADD standings_id INT NULL');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA7F97F032 FOREIGN KEY (standings_id) REFERENCES standings (id)');
        $this->addSql('CREATE INDEX IDX_62615BA7F97F032 ON matches (standings_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA7F97F032');
        $this->addSql('DROP INDEX IDX_62615BA7F97F032 ON matches');
        $this->addSql('ALTER TABLE matches DROP standings_id');
    }
}
