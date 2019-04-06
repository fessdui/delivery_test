<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190406122459
 * @package DoctrineMigrations
 */
final class Version20190406122459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create needed tables for our application.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, travel_to VARCHAR(255) NOT NULL, travel_back VARCHAR(255) NOT NULL, time_zone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courier (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, surname LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, courier_id INT NOT NULL, dispatch_time DATETIME NOT NULL, arrival_time DATETIME NOT NULL, INDEX IDX_5A3811FBC7209D4F (region_id), INDEX IDX_5A3811FBCCC02335 (courier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBC7209D4F FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBCCC02335 FOREIGN KEY (courier_id) REFERENCES courier (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBC7209D4F');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBCCC02335');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE courier');
        $this->addSql('DROP TABLE schedule');
    }
}
