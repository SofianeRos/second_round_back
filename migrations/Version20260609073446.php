<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260609073446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, raison VARCHAR(255) NOT NULL, date_signalement DATETIME NOT NULL, statut VARCHAR(30) NOT NULL, message_id INT NOT NULL, signale_par_id INT NOT NULL, INDEX IDX_F4B55114537A1329 (message_id), INDEX IDX_F4B55114AE190A20 (signale_par_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114537A1329 FOREIGN KEY (message_id) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114AE190A20 FOREIGN KEY (signale_par_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD banni TINYINT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114537A1329');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114AE190A20');
        $this->addSql('DROP TABLE signalement');
        $this->addSql('ALTER TABLE user DROP banni');
    }
}
