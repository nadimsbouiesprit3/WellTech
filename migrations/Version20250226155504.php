<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226155504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE progression (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, defi_id_id INT NOT NULL, statut VARCHAR(255) NOT NULL, progression INT DEFAULT NULL, date_completion DATETIME DEFAULT NULL, INDEX IDX_D5B250739D86650F (user_id_id), INDEX IDX_D5B2507361667BF5 (defi_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recompense (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, points_requis INT NOT NULL, type VARCHAR(255) NOT NULL, statu TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B250739D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B2507361667BF5 FOREIGN KEY (defi_id_id) REFERENCES defi (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B250739D86650F');
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B2507361667BF5');
        $this->addSql('DROP TABLE progression');
        $this->addSql('DROP TABLE recompense');
    }
}
