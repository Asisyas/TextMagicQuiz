<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010173406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE answer_correct_combinations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, answer_correct_combinations_id INT DEFAULT NULL, question_id INT NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A25F8A0C8EF ON answer (answer_correct_combinations_id)');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE TABLE answer_correct_combinations (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, answer_correct_combinations_id INT NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6F7494EF8A0C8EF ON question (answer_correct_combinations_id)');
        $this->addSql('CREATE TABLE quiz (id INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_id VARCHAR(255) NOT NULL, status INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN quiz.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE quiz_question (quiz_id INT NOT NULL, question_id INT NOT NULL, PRIMARY KEY(quiz_id, question_id))');
        $this->addSql('CREATE INDEX IDX_6033B00B853CD175 ON quiz_question (quiz_id)');
        $this->addSql('CREATE INDEX IDX_6033B00B1E27F6BF ON quiz_question (question_id)');
        $this->addSql('CREATE TABLE quiz_answer (id INT NOT NULL, question_id INT DEFAULT NULL, quiz_id INT NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX idx_quiz_question_unique ON quiz_answer (quiz_id, question_id)');
        $this->addSql('CREATE UNIQUE INDEX IDX_QUIZ_QUESTION_UNIQUE ON quiz_answer (quiz_id, question_id)');
        $this->addSql('CREATE INDEX IDX_3799BA7C1E27F6BF ON quiz_answer (question_id)');
        $this->addSql('CREATE INDEX IDX_3799BA7C853CD175 ON quiz_answer (quiz_id)');
        $this->addSql('CREATE TABLE quiz_answer_answer (quiz_answer_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(quiz_answer_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_29DA293AC5339E1 ON quiz_answer_answer (quiz_answer_id)');
        $this->addSql('CREATE INDEX IDX_29DA293AA334807 ON quiz_answer_answer (answer_id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25F8A0C8EF FOREIGN KEY (answer_correct_combinations_id) REFERENCES answer_correct_combinations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EF8A0C8EF FOREIGN KEY (answer_correct_combinations_id) REFERENCES answer_correct_combinations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_answer ADD CONSTRAINT FK_3799BA7C1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_answer ADD CONSTRAINT FK_3799BA7C853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_answer ADD is_correct BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE quiz_answer_answer ADD CONSTRAINT FK_29DA293AC5339E1 FOREIGN KEY (quiz_answer_id) REFERENCES quiz_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_answer_answer ADD CONSTRAINT FK_29DA293AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE answer_correct_combinations_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_answer_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A25F8A0C8EF');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494EF8A0C8EF');
        $this->addSql('ALTER TABLE quiz_question DROP CONSTRAINT FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP CONSTRAINT FK_6033B00B1E27F6BF');
        $this->addSql('DROP INDEX idx_quiz_question_unique');
        $this->addSql('ALTER TABLE quiz_answer DROP CONSTRAINT FK_3799BA7C1E27F6BF');
        $this->addSql('ALTER TABLE quiz_answer DROP CONSTRAINT FK_3799BA7C853CD175');
        $this->addSql('ALTER TABLE quiz_answer_answer DROP CONSTRAINT FK_29DA293AC5339E1');
        $this->addSql('ALTER TABLE quiz_answer_answer DROP CONSTRAINT FK_29DA293AA334807');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE answer_correct_combinations');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE quiz_answer');
        $this->addSql('DROP TABLE quiz_answer_answer');
    }
}
