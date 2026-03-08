<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250106103032 extends AbstractMigration
{
    private string $table = 'employee_profile';

    public function getDescription(): string
    {
        return 'Create ' . $this->table . ' table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable($this->table);

        $table->addColumn('id', 'bigint', [
            'autoincrement' => true,
            'notnull' => true,
        ]);

        $table->addColumn('employee_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('name', 'string', [
            'length' => 64,
            'notnull' => true,
        ]);

        $table->addColumn('surname', 'string', [
            'length' => 64,
            'notnull' => true,
        ]);

        $table->addColumn('birthdate', 'date_immutable', [
            'notnull' => false,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addUniqueIndex(['name', 'surname'], 'uniq_'.$this->table.'_name_surname');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
