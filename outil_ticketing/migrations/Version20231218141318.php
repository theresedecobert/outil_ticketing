<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218141318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answers (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, description LONGTEXT NOT NULL, resoluted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', doc_link VARCHAR(255) DEFAULT NULL, INDEX IDX_50D0C606A76ED395 (user_id), INDEX IDX_50D0C606700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, ticket_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, INDEX IDX_6354059700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, status VARCHAR(20) NOT NULL, title VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_54469DF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606A76ED395');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606700047D2');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059700047D2');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4A76ED395');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
