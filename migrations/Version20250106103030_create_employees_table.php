<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250106103030 extends AbstractMigration
{
    /** string $table */
    private string $table = 'employees';

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

        $table->addColumn('uuid', 'uuid', [
            'notnull' => true,
            'comment' => 'UUIDv7',
        ]);

        $table->addColumn('is_active', 'boolean', [
            'default' => false
        ]);

        $table->addColumn('is_banned', 'boolean', [
            'default' => false
        ]);

        $table->addColumn('created_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('users', ['user_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addUniqueIndex(['uuid'], 'uniq_'.$this->table.'_uuid');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ' . $this->table);
    }
}
