<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512114352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, prix_final NUMERIC(10, 2) NOT NULL, frais_port NUMERIC(10, 2) NOT NULL, statut_livraison VARCHAR(255) NOT NULL, date_commande DATETIME NOT NULL, acheteur_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_6EEAA67D96A7BB5F (acheteur_id), INDEX IDX_6EEAA67D7294869C (article_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commentaire_article (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, date_publication DATETIME NOT NULL, auteur_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_71F29C3560BB6FE6 (auteur_id), INDEX IDX_71F29C357294869C (article_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, commentaire LONGTEXT DEFAULT NULL, date_evaluation DATETIME NOT NULL, user_auteur_id INT NOT NULL, user_cible_id INT NOT NULL, INDEX IDX_1323A575605BE202 (user_auteur_id), INDEX IDX_1323A57549E3BA09 (user_cible_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messagerie (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, est_offre TINYINT NOT NULL, montant_offre NUMERIC(10, 2) DEFAULT NULL, statut_offre VARCHAR(255) DEFAULT NULL, date_envoie DATETIME NOT NULL, expediteur_id INT NOT NULL, destinataire_id INT NOT NULL, article_id INT DEFAULT NULL, INDEX IDX_14E8F60C10335F61 (expediteur_id), INDEX IDX_14E8F60CA4F84F6E (destinataire_id), INDEX IDX_14E8F60C7294869C (article_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D96A7BB5F FOREIGN KEY (acheteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire_article ADD CONSTRAINT FK_71F29C3560BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire_article ADD CONSTRAINT FK_71F29C357294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575605BE202 FOREIGN KEY (user_auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57549E3BA09 FOREIGN KEY (user_cible_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C10335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60CA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D96A7BB5F');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D7294869C');
        $this->addSql('ALTER TABLE commentaire_article DROP FOREIGN KEY FK_71F29C3560BB6FE6');
        $this->addSql('ALTER TABLE commentaire_article DROP FOREIGN KEY FK_71F29C357294869C');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575605BE202');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57549E3BA09');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C10335F61');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60CA4F84F6E');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C7294869C');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire_article');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE messagerie');
    }
}
