<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Only admins can manage employment_workdays after it was created
 * Records any action_key except the creation of employment_workdays entity
 */
final class Version20250107103036 extends AbstractMigration
{
    private string $table = 'employment_workday_clockings_logs';

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

        $table->addColumn('clocking_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('admin_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('action_key', 'string', [
            'length' => 128,
            'notnull' => true,
        ]);

        $table->addColumn('created_at', 'datetime_immutable', [
            'notnull' => true,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('employment_workday_clockings', ['clocking_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addForeignKeyConstraint('admins', ['admin_id'], ['id'], ['onDelete' => 'SET NULL']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
