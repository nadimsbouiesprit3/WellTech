<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226163339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B2507361667BF5');
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B250739D86650F');
        $this->addSql('DROP INDEX IDX_D5B2507361667BF5 ON progression');
        $this->addSql('DROP INDEX IDX_D5B250739D86650F ON progression');
        $this->addSql('ALTER TABLE progression ADD user_id INT NOT NULL, ADD defi_id INT NOT NULL, DROP user_id_id, DROP defi_id_id');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B25073A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B2507373F00F27 FOREIGN KEY (defi_id) REFERENCES defi (id)');
        $this->addSql('CREATE INDEX IDX_D5B25073A76ED395 ON progression (user_id)');
        $this->addSql('CREATE INDEX IDX_D5B2507373F00F27 ON progression (defi_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B25073A76ED395');
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B2507373F00F27');
        $this->addSql('DROP INDEX IDX_D5B25073A76ED395 ON progression');
        $this->addSql('DROP INDEX IDX_D5B2507373F00F27 ON progression');
        $this->addSql('ALTER TABLE progression ADD user_id_id INT NOT NULL, ADD defi_id_id INT NOT NULL, DROP user_id, DROP defi_id');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B2507361667BF5 FOREIGN KEY (defi_id_id) REFERENCES defi (id)');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B250739D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D5B2507361667BF5 ON progression (defi_id_id)');
        $this->addSql('CREATE INDEX IDX_D5B250739D86650F ON progression (user_id_id)');
    }
}
