<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * From employment_workdays gets latest employee record id and will set clockin and clockout records using the same employment_workdays.id
 * The logic is by taking employment_workdays.stars_date as entry point, by then
 * sums employment_contracts.hours_per_day + employment_workdays.hours_extra againts the total time between every clockin / clockout
 * in order to keep using that last register id. Clockout can be recorded exceding the total time of the workday.
 * On the other hand, if new clockin is requested beyond the calculation described, it has to be created a new employment_workdays.id
 */
final class Version20250107103035 extends AbstractMigration
{
    private string $table = 'employment_workday_clockings';

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

        $table->addColumn('workday_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('employee_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('clock_in', 'boolean', [
            'default' => false
        ]);

        $table->addColumn('clock_out', 'boolean', [
            'default' => false
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
        $table->addForeignKeyConstraint('employment_workdays', ['workday_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
