<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011224000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $questions_sql = explode("\n", file_get_contents(__DIR__ . '/question_collection_v2.sql'));
        foreach ($questions_sql as $sql) {
            if(!$sql) {
                continue;
            }

            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE UNIQUE INDEX idx_quiz_question_unique ON quiz_answer (quiz_id, question_id)');
    }
}
