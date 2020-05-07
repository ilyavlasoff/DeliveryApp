<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200507165123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE employee_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE delivery_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE appointment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE item_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE arrival_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE courier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE delivery_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vendor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE warehouse_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE work_shift_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE receiver_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE item (id BIGINT NOT NULL, delivery_id BIGINT DEFAULT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1B251E12136921 ON item (delivery_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E12469DE2 ON item (category_id)');
        $this->addSql('CREATE TABLE employee (id BIGINT NOT NULL, user_id INT DEFAULT NULL, appointment_id INT DEFAULT NULL, warehouse_id INT DEFAULT NULL, passport CHAR(10) NOT NULL, name VARCHAR(30) NOT NULL, surname VARCHAR(40) NOT NULL, patronymic VARCHAR(40) DEFAULT NULL, birthday DATE NOT NULL, oms_num CHAR(16) NOT NULL, inn CHAR(12) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1A76ED395 ON employee (user_id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A1E5B533F9 ON employee (appointment_id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A15080ECDE ON employee (warehouse_id)');
        $this->addSql('CREATE TABLE delivery (id BIGINT NOT NULL, type_id INT DEFAULT NULL, vendor_id INT DEFAULT NULL, receiver_id BIGINT DEFAULT NULL, dep_city VARCHAR(30) NOT NULL, dep_country VARCHAR(30) NOT NULL, dep_street VARCHAR(40) NOT NULL, dep_building VARCHAR(5) NOT NULL, dep_flat INT DEFAULT NULL, dep_postcode VARCHAR(20) NOT NULL, dest_city VARCHAR(30) NOT NULL, dest_country VARCHAR(30) NOT NULL, dest_street VARCHAR(40) NOT NULL, dest_house VARCHAR(5) NOT NULL, dest_flat INT DEFAULT NULL, dest_postcode VARCHAR(20) NOT NULL, route_length DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3781EC10C54C8C93 ON delivery (type_id)');
        $this->addSql('CREATE INDEX IDX_3781EC10F603EE73 ON delivery (vendor_id)');
        $this->addSql('CREATE INDEX IDX_3781EC10CD53EDB6 ON delivery (receiver_id)');
        $this->addSql('CREATE TABLE appointment (id INT NOT NULL, appointment_name VARCHAR(30) NOT NULL, salary NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item_category (id INT NOT NULL, parent_id_cat INT DEFAULT NULL, name VARCHAR(40) NOT NULL, fire_danger BOOLEAN NOT NULL, toxic BOOLEAN NOT NULL, explosive BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A41D10A4FE96834 ON item_category (parent_id_cat)');
        $this->addSql('CREATE TABLE arrival (id BIGINT NOT NULL, delivery_id BIGINT DEFAULT NULL, warehouse_id INT DEFAULT NULL, arrival_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, departure_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, storage INT NOT NULL, shelf INT NOT NULL, place INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5BE55CB412136921 ON arrival (delivery_id)');
        $this->addSql('CREATE INDEX IDX_5BE55CB45080ECDE ON arrival (warehouse_id)');
        $this->addSql('CREATE TABLE status_history (id BIGINT NOT NULL, delivery_id BIGINT DEFAULT NULL, status_code VARCHAR(5) DEFAULT NULL, status_comment VARCHAR(50) DEFAULT NULL, status_set_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2F6A07CE12136921 ON status_history (delivery_id)');
        $this->addSql('CREATE INDEX IDX_2F6A07CE4F139D0C ON status_history (status_code)');
        $this->addSql('CREATE TABLE status_codes (scode VARCHAR(5) NOT NULL, title VARCHAR(30) DEFAULT NULL, PRIMARY KEY(scode))');
        $this->addSql('CREATE TABLE payments (id BIGINT NOT NULL, delivery_id BIGINT DEFAULT NULL, sum NUMERIC(10, 0) NOT NULL, status VARCHAR(10) NOT NULL, uip VARCHAR(25) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_65D29B3212136921 ON payments (delivery_id)');
        $this->addSql('CREATE TABLE courier (id INT NOT NULL, emp_id BIGINT DEFAULT NULL, drive_cat JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CF134C7C7A663008 ON courier (emp_id)');
        $this->addSql('CREATE TABLE delivery_type (id INT NOT NULL, name VARCHAR(20) NOT NULL, max_distance DOUBLE PRECISION NOT NULL, price NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE vendor (id INT NOT NULL, user_id INT DEFAULT NULL, ogrn CHAR(13) NOT NULL, name VARCHAR(100) NOT NULL, cor_acc CHAR(20) DEFAULT NULL, bik CHAR(9) NOT NULL, bank_city VARCHAR(100) NOT NULL, kpp CHAR(9) NOT NULL, inn CHAR(12) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52233F6A76ED395 ON vendor (user_id)');
        $this->addSql('CREATE TABLE auto (number CHAR(8) NOT NULL, model VARCHAR(30) DEFAULT NULL, required_drive_cat VARCHAR(3) NOT NULL, capacity DOUBLE PRECISION NOT NULL, is_functional BOOLEAN NOT NULL, PRIMARY KEY(number))');
        $this->addSql('CREATE TABLE warehouse (id INT NOT NULL, country VARCHAR(30) NOT NULL, region VARCHAR(60) NOT NULL, city VARCHAR(50) NOT NULL, street VARCHAR(50) NOT NULL, building VARCHAR(10) NOT NULL, max_contain DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE work_shift (id BIGINT NOT NULL, courier_id INT DEFAULT NULL, auto_num CHAR(8) DEFAULT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8DF0406AE3D8151C ON work_shift (courier_id)');
        $this->addSql('CREATE INDEX IDX_8DF0406AFC8E4603 ON work_shift (auto_num)');
        $this->addSql('CREATE TABLE receiver (id BIGINT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, surname VARCHAR(40) NOT NULL, patronymic VARCHAR(40) DEFAULT NULL, passport CHAR(10) DEFAULT NULL, phone CHAR(11) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3DB88C96A76ED395 ON receiver (user_id)');
        $this->addSql('CREATE TABLE carry (workshift_id BIGINT NOT NULL, delivery_id BIGINT NOT NULL, from_warehouse INT DEFAULT NULL, to_warehouse INT DEFAULT NULL, PRIMARY KEY(workshift_id, delivery_id))');
        $this->addSql('CREATE INDEX IDX_F88F04341AF3E551 ON carry (workshift_id)');
        $this->addSql('CREATE INDEX IDX_F88F043412136921 ON carry (delivery_id)');
        $this->addSql('CREATE INDEX IDX_F88F0434FC2F1E46 ON carry (from_warehouse)');
        $this->addSql('CREATE INDEX IDX_F88F0434DD105C8D ON carry (to_warehouse)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E12469DE2 FOREIGN KEY (category_id) REFERENCES item_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A15080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10C54C8C93 FOREIGN KEY (type_id) REFERENCES delivery_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_category ADD CONSTRAINT FK_6A41D10A4FE96834 FOREIGN KEY (parent_id_cat) REFERENCES item_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arrival ADD CONSTRAINT FK_5BE55CB412136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arrival ADD CONSTRAINT FK_5BE55CB45080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_history ADD CONSTRAINT FK_2F6A07CE12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_history ADD CONSTRAINT FK_2F6A07CE4F139D0C FOREIGN KEY (status_code) REFERENCES status_codes (scode) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3212136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE courier ADD CONSTRAINT FK_CF134C7C7A663008 FOREIGN KEY (emp_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vendor ADD CONSTRAINT FK_F52233F6A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_shift ADD CONSTRAINT FK_8DF0406AE3D8151C FOREIGN KEY (courier_id) REFERENCES courier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_shift ADD CONSTRAINT FK_8DF0406AFC8E4603 FOREIGN KEY (auto_num) REFERENCES auto (number) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receiver ADD CONSTRAINT FK_3DB88C96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE carry ADD CONSTRAINT FK_F88F04341AF3E551 FOREIGN KEY (workshift_id) REFERENCES work_shift (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE carry ADD CONSTRAINT FK_F88F043412136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE carry ADD CONSTRAINT FK_F88F0434FC2F1E46 FOREIGN KEY (from_warehouse) REFERENCES warehouse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE carry ADD CONSTRAINT FK_F88F0434DD105C8D FOREIGN KEY (to_warehouse) REFERENCES warehouse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE courier DROP CONSTRAINT FK_CF134C7C7A663008');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E12136921');
        $this->addSql('ALTER TABLE arrival DROP CONSTRAINT FK_5BE55CB412136921');
        $this->addSql('ALTER TABLE status_history DROP CONSTRAINT FK_2F6A07CE12136921');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B3212136921');
        $this->addSql('ALTER TABLE carry DROP CONSTRAINT FK_F88F043412136921');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1E5B533F9');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E12469DE2');
        $this->addSql('ALTER TABLE item_category DROP CONSTRAINT FK_6A41D10A4FE96834');
        $this->addSql('ALTER TABLE status_history DROP CONSTRAINT FK_2F6A07CE4F139D0C');
        $this->addSql('ALTER TABLE work_shift DROP CONSTRAINT FK_8DF0406AE3D8151C');
        $this->addSql('ALTER TABLE delivery DROP CONSTRAINT FK_3781EC10C54C8C93');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1A76ED395');
        $this->addSql('ALTER TABLE vendor DROP CONSTRAINT FK_F52233F6A76ED395');
        $this->addSql('ALTER TABLE receiver DROP CONSTRAINT FK_3DB88C96A76ED395');
        $this->addSql('ALTER TABLE delivery DROP CONSTRAINT FK_3781EC10F603EE73');
        $this->addSql('ALTER TABLE work_shift DROP CONSTRAINT FK_8DF0406AFC8E4603');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A15080ECDE');
        $this->addSql('ALTER TABLE arrival DROP CONSTRAINT FK_5BE55CB45080ECDE');
        $this->addSql('ALTER TABLE carry DROP CONSTRAINT FK_F88F0434FC2F1E46');
        $this->addSql('ALTER TABLE carry DROP CONSTRAINT FK_F88F0434DD105C8D');
        $this->addSql('ALTER TABLE carry DROP CONSTRAINT FK_F88F04341AF3E551');
        $this->addSql('ALTER TABLE delivery DROP CONSTRAINT FK_3781EC10CD53EDB6');
        $this->addSql('DROP SEQUENCE item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE employee_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE delivery_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE appointment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE item_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE arrival_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE courier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE delivery_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE vendor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE warehouse_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE work_shift_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE receiver_id_seq CASCADE');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE item_category');
        $this->addSql('DROP TABLE arrival');
        $this->addSql('DROP TABLE status_history');
        $this->addSql('DROP TABLE status_codes');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE courier');
        $this->addSql('DROP TABLE delivery_type');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE vendor');
        $this->addSql('DROP TABLE auto');
        $this->addSql('DROP TABLE warehouse');
        $this->addSql('DROP TABLE work_shift');
        $this->addSql('DROP TABLE receiver');
        $this->addSql('DROP TABLE carry');
    }
}
