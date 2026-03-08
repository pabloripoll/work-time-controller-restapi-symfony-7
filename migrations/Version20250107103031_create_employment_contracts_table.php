<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250107103031 extends AbstractMigration
{
    private string $table = 'employment_contracts';

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

        $table->addColumn('contract_type_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('employee_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('admin_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('days_per_month', 'integer', [
            'default' => 0,
            'notnull' => true,
        ]);

        $table->addColumn('days_per_week', 'integer', [
            'default' => 0,
            'notnull' => true,
        ]);

        $table->addColumn('hours_per_day', 'integer', [
            'default' => 0,
            'notnull' => true,
        ]);

        $table->addColumn('has_contract_signed', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('has_grpd_signed', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('has_lopd_signed', 'boolean', [
            'default' => false,
            'notnull' => true,
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
        $table->addForeignKeyConstraint('employment_contract_types', ['contract_type_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addForeignKeyConstraint('admins', ['admin_id'], ['id'], ['onDelete' => 'SET NULL']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
