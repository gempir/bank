<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110122733 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bank_transaction_part (id INT AUTO_INCREMENT NOT NULL, bank_transaction_id INT NOT NULL, reason VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, INDEX IDX_A2059A7EB898B7D6 (bank_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_transaction (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(60) NOT NULL, amount NUMERIC(10, 2) NOT NULL, booking_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_transaction_part ADD CONSTRAINT FK_A2059A7EB898B7D6 FOREIGN KEY (bank_transaction_id) REFERENCES bank_transaction (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_transaction_part DROP FOREIGN KEY FK_A2059A7EB898B7D6');
        $this->addSql('DROP TABLE bank_transaction_part');
        $this->addSql('DROP TABLE bank_transaction');
    }
}
