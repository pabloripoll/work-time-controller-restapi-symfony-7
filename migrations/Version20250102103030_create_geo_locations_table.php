<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250102103030 extends AbstractMigration
{
    /** string $table */
    private string $table = 'geo_locations';

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

        $table->addColumn('is_continent', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('continent_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('is_zone', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('zone_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('is_country', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('country_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('is_region', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('region_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('is_state', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('state_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('is_district', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('district_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('is_city', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('city_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('is_suburb', 'boolean', [
            'default' => false,
            'notnull' => true,
        ]);

        $table->addColumn('suburb_id', 'bigint', [
            'notnull' => false
        ]);

        $table->addColumn('slug', 'string', [
            'length' => 128,
            'notnull' => true,
        ]);

        $table->addColumn('name', 'string', [
            'length' => 128,
            'notnull' => true,
        ]);

        $table->addColumn('created_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'notnull' => true,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['slug'], 'uniq_'.$this->table.'_slug');
        $table->addUniqueIndex(['name'], 'uniq_'.$this->table.'_name');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ' . $this->table);
    }
}
