<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * employment_workdays is created by system on first attempt to clockin by the employee
 */
final class Version20250107103033 extends AbstractMigration
{
    private string $table = 'employment_workdays';

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

        $table->addColumn('contract_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('employee_id', 'bigint', [
            'notnull' => true
        ]);

        // On first clocking sets the start time of the workday
        $table->addColumn('starts_date', 'datetime_immutable', [
            'notnull' => false,
        ]);

        // Depending on employment_contracts.hours_per_day it is set the ends_date
        $table->addColumn('ends_date', 'datetime_immutable', [
            'notnull' => false,
        ]);

        // hours_extra can only be added by admin
        $table->addColumn('hours_extra', 'time', [
            'notnull' => false,
        ]);

        // hours_made sum the total time of the employment_workday_clockings
        $table->addColumn('hours_made', 'time', [
            'notnull' => false,
        ]);

        $table->addColumn('created_at', 'datetime_immutable', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime_immutable', [
            'notnull' => true,
        ]);

        $table->addColumn('deleted_at', 'datetime_immutable', [
            'notnull' => false,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['starts_date'], 'idx_' . $this->table . '_start_date');
        $table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addForeignKeyConstraint('employment_contracts', ['contract_id'], ['id'], ['onDelete' => 'SET NULL']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
