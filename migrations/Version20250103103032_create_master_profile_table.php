<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250103103033 extends AbstractMigration
{
    private string $table = 'master_profile';

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

        $table->addColumn('master_id', 'bigint', [
            'notnull' => true
        ]);

        $table->addColumn('nickname', 'string', [
            'length' => 64,
            'notnull' => true,
        ]);

        $table->addColumn('avatar', 'text', [
            'notnull' => false,
        ]);

        $table->addColumn('created_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('masters', ['master_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->table);
    }
}
