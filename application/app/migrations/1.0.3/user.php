<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;
use Phalcon\Db\Adapter\Pdo\Postgresql;

/**
 * Class UserMigration_103
 */
class UserMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     * @throws Exception
     */
    public function morph(): void
    {
        $this->morphTable('user', [
            'columns' => [
                new Column(
                    'first_name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => false,
                        'size' => 255,
                        'first' => true
                    ]
                ),
                new Column(
                    'second_name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => false,
                        'size' => 255,
                        'after' => 'first_name'
                    ]
                ),
                new Column(
                    'patronymic',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => false,
                        'size' => 255,
                        'after' => 'second_name'
                    ]
                ),
                new Column(
                    'created_at',
                    [
                        'type' => Column::TYPE_TIMESTAMP,
                        'notNull' => false,
                        'after' => 'patronymic'
                    ]
                ),
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'primary' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'after' => 'created_at'
                    ]
                ),
            ],
        ]);
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up(): void
    {

        $firstNames     = ['Ivan', 'Petr', 'Alexandr'];
        $secondNames    = ['Ivanov', 'Petrov', 'Alexandrov'];
        $patronymic     = ['Ivanovich', 'Petrovich', 'Alexandrovich'];

        self::$connection->execute('ALTER TABLE "user" ADD fio tsvector');
        self::$connection->execute('CREATE INDEX fio_idx ON "user" USING GIN (fio)');
        for($i = 0; $i <= 1000; $i++){

            $curTime = new \DateTime();
            self::$connection->insert(
                'user',
                [
                    $firstNames[array_rand($patronymic)],
                    $secondNames[array_rand($patronymic)],
                    $patronymic[array_rand($patronymic)],
                    $curTime->format(DateTimeInterface::ATOM)
                ]
            );
        }
        self::$connection->execute('UPDATE "user" SET
            fio = to_tsvector(\'russian\', coalesce(first_name,\'\') || \' \' || coalesce(second_name,\'\') || \' \' || coalesce(patronymic,\'\'))
            WHERE id > 0'
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
    }
}
