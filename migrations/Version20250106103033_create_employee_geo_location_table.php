<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250106103033 extends AbstractMigration
{
    /** string $table */
    private string $table = 'employee_geo_location';

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

        $table->addColumn('continent_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('zone_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('country_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('region_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('state_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('district_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('city_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('suburb_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('created_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->addColumn('address', 'string', [
            'length' => 128,
            'notnull' => false,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('employees', ['employee_id'], ['id'], ['onDelete' => 'CASCADE']);
        $table->addForeignKeyConstraint('geo_locations', ['continent_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('geo_locations', ['zone_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('geo_locations', ['country_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('geo_locations', ['region_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('geo_locations', ['state_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('geo_locations', ['district_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('geo_locations', ['city_id'], ['id'], ['onDelete' => 'SET NULL']);
        $table->addForeignKeyConstraint('geo_locations', ['suburb_id'], ['id'], ['onDelete' => 'SET NULL']);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ' . $this->table);
    }
}
