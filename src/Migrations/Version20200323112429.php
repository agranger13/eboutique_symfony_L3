<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200323112429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE command_line (id INT AUTO_INCREMENT NOT NULL, orders_id INT NOT NULL, item_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_70BE1A7BCFFE9AD6 (orders_id), INDEX IDX_70BE1A7B126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, category_id INT DEFAULT NULL, type VARCHAR(32) NOT NULL, path VARCHAR(255) NOT NULL, alt VARCHAR(50) NOT NULL, INDEX IDX_6A2CA10C4584665A (product_id), UNIQUE INDEX UNIQ_6A2CA10C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(20) NOT NULL, name VARCHAR(32) NOT NULL, first_name VARCHAR(32) NOT NULL, phone INT NOT NULL, address VARCHAR(255) NOT NULL, cp INT NOT NULL, city VARCHAR(32) NOT NULL, country VARCHAR(31) NOT NULL, INDEX IDX_5543718BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7B126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4584665A FOREIGN KEY (product_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE payment_info');
        $this->addSql('ALTER TABLE category ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD phone INT NOT NULL, DROP birth_date, DROP address');
        $this->addSql('ALTER TABLE item CHANGE stock available INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD date_time DATETIME NOT NULL, ADD valid INT NOT NULL, DROP total');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, order_id_id INT NOT NULL, item_id_id INT NOT NULL, INDEX IDX_27BA704B55E38587 (item_id_id), INDEX IDX_27BA704BFCDAEAAA (order_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE payment_info (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(10) NOT NULL COLLATE utf8mb4_unicode_ci, number INT NOT NULL, expires DATETIME NOT NULL, cvv INT NOT NULL, name_holder VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_EA0AA623A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B55E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE payment_info ADD CONSTRAINT FK_EA0AA623A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE command_line');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('ALTER TABLE category DROP description');
        $this->addSql('ALTER TABLE item CHANGE available stock INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD total DOUBLE PRECISION NOT NULL, DROP date_time, DROP valid');
        $this->addSql('ALTER TABLE user ADD birth_date DATE NOT NULL, ADD address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP phone');
    }
}
