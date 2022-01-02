<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102203011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posts_posts (posts_source INT NOT NULL, posts_target INT NOT NULL, PRIMARY KEY(posts_source, posts_target))');
        $this->addSql('CREATE INDEX IDX_3A5AE1EB358858DA ON posts_posts (posts_source)');
        $this->addSql('CREATE INDEX IDX_3A5AE1EB2C6D0855 ON posts_posts (posts_target)');
        $this->addSql('ALTER TABLE posts_posts ADD CONSTRAINT FK_3A5AE1EB358858DA FOREIGN KEY (posts_source) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts_posts ADD CONSTRAINT FK_3A5AE1EB2C6D0855 FOREIGN KEY (posts_target) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN posts.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE posts_posts');
        $this->addSql('ALTER TABLE posts DROP created_at');
    }
}
