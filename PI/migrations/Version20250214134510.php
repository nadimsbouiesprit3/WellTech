<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214134510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE defi (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, points INT NOT NULL, statut TINYINT(1) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, conditions JSON NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE progression (id INT AUTO_INCREMENT NOT NULL, utilisateur_id_id INT NOT NULL, defi_id_id INT NOT NULL, statut VARCHAR(255) NOT NULL, progression INT DEFAULT NULL, date_completion DATETIME DEFAULT NULL, INDEX IDX_D5B25073B981C689 (utilisateur_id_id), INDEX IDX_D5B2507361667BF5 (defi_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recompense (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, descritption LONGTEXT NOT NULL, points_requis INT NOT NULL, type VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B25073B981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B2507361667BF5 FOREIGN KEY (defi_id_id) REFERENCES defi (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B25073B981C689');
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B2507361667BF5');
        $this->addSql('DROP TABLE defi');
        $this->addSql('DROP TABLE progression');
        $this->addSql('DROP TABLE recompense');
        $this->addSql('DROP TABLE user');
    }
}
