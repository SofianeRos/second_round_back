<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512081202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, taille VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, prix NUMERIC(10, 2) NOT NULL, description LONGTEXT DEFAULT NULL, date_publication DATETIME NOT NULL, vendeur_id INT NOT NULL, statut_id INT NOT NULL, INDEX IDX_23A0E66858C065E (vendeur_id), INDEX IDX_23A0E66F6203804 (statut_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, couleur_badge VARCHAR(255) DEFAULT NULL, par_defaut TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL, taille_cm INT DEFAULT NULL, poids_kg INT DEFAULT NULL, niveau VARCHAR(255) DEFAULT NULL, type_boxe VARCHAR(255) DEFAULT NULL, budget_max NUMERIC(10, 2) DEFAULT NULL, date_inscription DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66858C065E FOREIGN KEY (vendeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66858C065E');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F6203804');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE user');
    }
}
