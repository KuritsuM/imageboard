<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227182427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE boards_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE posts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE threads_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE boards (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE posts (id INT NOT NULL, thread_id INT DEFAULT NULL, theme VARCHAR(255) DEFAULT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_885DBAFAE2904019 ON posts (thread_id)');
        $this->addSql('CREATE TABLE threads (id INT NOT NULL, board_id INT DEFAULT NULL, theme VARCHAR(255) DEFAULT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6F8E3DDDE7EC5785 ON threads (board_id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAE2904019 FOREIGN KEY (thread_id) REFERENCES threads (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE threads ADD CONSTRAINT FK_6F8E3DDDE7EC5785 FOREIGN KEY (board_id) REFERENCES boards (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE threads DROP CONSTRAINT FK_6F8E3DDDE7EC5785');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFAE2904019');
        $this->addSql('DROP SEQUENCE boards_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE posts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE threads_id_seq CASCADE');
        $this->addSql('DROP TABLE boards');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE threads');
    }
}
