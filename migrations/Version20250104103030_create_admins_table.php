<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250104103030 extends AbstractMigration
{
    /** string $table */
    private string $table = 'admins';

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

        $table->addColumn('user_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('is_active', 'boolean', [
            'default' => false
        ]);

        $table->addColumn('is_banned', 'boolean', [
            'default' => false
        ]);

        $table->addColumn('is_superadmin', 'boolean', [
            'default' => false
        ]);

        $table->addColumn('employee_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('created_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['is_superadmin'], 'idx_' . $this->table . '_is_superadmin');
        $table->addForeignKeyConstraint('users', ['user_id'], ['id'], ['onDelete' => 'CASCADE']);
        //$table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'SET NULL']); // NOT
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ' . $this->table);
    }
}
