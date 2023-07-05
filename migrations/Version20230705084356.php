<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705084356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, team1_id INT NOT NULL, team2_id INT NOT NULL, score1 INT NOT NULL, score2 INT NOT NULL, date_time DATETIME NOT NULL, referee VARCHAR(255) NOT NULL, INDEX IDX_62615BAE72BCFA4 (team1_id), INDEX IDX_62615BAF59E604A (team2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, investment INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor_team (sponsor_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_2442E5FF12F7FB51 (sponsor_id), INDEX IDX_2442E5FF296CD8AE (team_id), PRIMARY KEY(sponsor_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAE72BCFA4 FOREIGN KEY (team1_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAF59E604A FOREIGN KEY (team2_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE sponsor_team ADD CONSTRAINT FK_2442E5FF12F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sponsor_team ADD CONSTRAINT FK_2442E5FF296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAE72BCFA4');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAF59E604A');
        $this->addSql('ALTER TABLE sponsor_team DROP FOREIGN KEY FK_2442E5FF12F7FB51');
        $this->addSql('ALTER TABLE sponsor_team DROP FOREIGN KEY FK_2442E5FF296CD8AE');
        $this->addSql('DROP TABLE matches');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE sponsor_team');
    }
}
