<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250106103034 extends AbstractMigration
{
    private string $table = 'employee_contacts';

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

        $table->addColumn('postal', 'string', [
            'length' => 64,
            'notnull' => false,
        ]);

        $table->addColumn('email', 'string', [
            'length' => 64,
            'notnull' => false,
        ]);

        $table->addColumn('phone', 'string', [
            'length' => 64,
            'notnull' => false,
        ]);

        $table->addColumn('mobile', 'string', [
            'length' => 64,
            'notnull' => false,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
