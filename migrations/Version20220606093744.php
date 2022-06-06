<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220606093744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE author_id author_id INT DEFAULT NULL, CHANGE episode_id episode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE program ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED7784F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_92ED7784F675F31B ON program (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE author_id author_id INT NOT NULL, CHANGE episode_id episode_id INT NOT NULL');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED7784F675F31B');
        $this->addSql('DROP INDEX IDX_92ED7784F675F31B ON program');
        $this->addSql('ALTER TABLE program DROP author_id');
    }
}
