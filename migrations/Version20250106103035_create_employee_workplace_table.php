<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250106103035 extends AbstractMigration
{
    /** string $table */
    private string $table = 'employee_workplace';

    public function getDescription(): string
    {
        return 'Create '. $this->table .' table';
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

        $table->addColumn('department_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('job_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('created_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addForeignKeyConstraint('office_departments', ['department_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('office_jobs', ['job_id'], ['id'], ['onDelete' => 'SET NULL']);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ' . $this->table);
    }
}
