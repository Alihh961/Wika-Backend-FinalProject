<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230730122528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE media');
        $this->addSql('ALTER TABLE audio DROP name, DROP description, DROP url, DROP size, DROP format');
        $this->addSql('ALTER TABLE image DROP name, DROP description, DROP url, DROP size, DROP format');
        $this->addSql('ALTER TABLE video DROP name, DROP description, DROP url, DROP size, DROP format');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, size INT NOT NULL, format VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE video ADD name VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD url VARCHAR(255) NOT NULL, ADD size INT NOT NULL, ADD format VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE audio ADD name VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD url VARCHAR(255) NOT NULL, ADD size INT NOT NULL, ADD format VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image ADD name VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD url VARCHAR(255) NOT NULL, ADD size INT NOT NULL, ADD format VARCHAR(255) NOT NULL');
    }
}
