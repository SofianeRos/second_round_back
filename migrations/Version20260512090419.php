<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512090419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favori (id INT AUTO_INCREMENT NOT NULL, date_ajout DATETIME NOT NULL, user_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_EF85A2CCA76ED395 (user_id), INDEX IDX_EF85A2CC7294869C (article_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, nom_fichier VARCHAR(255) NOT NULL, est_principale TINYINT NOT NULL, article_id INT NOT NULL, INDEX IDX_14B784187294869C (article_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784187294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CCA76ED395');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC7294869C');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784187294869C');
        $this->addSql('DROP TABLE favori');
        $this->addSql('DROP TABLE photo');
    }
}
