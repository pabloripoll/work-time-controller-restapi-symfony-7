<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250101103030 extends AbstractMigration
{
    private string $table = 'users';

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

        $table->addColumn('role', 'string', [
            'length' => 32,
            'notnull' => true,
        ]);

        $table->addColumn('email', 'string', [
            'length' => 64,
            'notnull' => true,
        ]);

        $table->addColumn('password', 'string', [
            'length' => 256,
            'notnull' => true,
        ]);

        $table->addColumn('created_at', 'datetime_immutable', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime_immutable', [
            'notnull' => false,
        ]);

        $table->addColumn('deleted_at', 'datetime_immutable', [
            'notnull' => false,
        ]);

        $table->addColumn('created_by_user_id', 'bigint', [
            'notnull' => true
        ]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['email'], 'uniq_' . $this->table . '_email');
        $table->addIndex(['role'], 'idx_' . $this->table . '_role');
        $table->addIndex(['created_at'], 'idx_' . $this->table . '_created_at');
        $table->addIndex(['deleted_at'], 'idx_' . $this->table . '_deleted_at');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
