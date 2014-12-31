<?php
require 'vendor/autoload.php';
require 'database.php';

const STRIKES_TABLE = 'strikes';

$builder = $capsule->getConnection()->getSchemaBuilder();
if ($builder->hasTable('strikes')) {
    $builder->drop('strikes');
}

$builder->create('strikes', function($table) {
    /**
     * @var $table \Illuminate\Database\Schema\Blueprint
     */
    $table->increments('id');
    $table->date('bulletin_date');
    $table->string('code', 2);
    $table->string('month', 5);
    $table->enum('type', [\Cme\Eloquent\Strike::CALL, \Cme\Eloquent\Strike::PUT]);
    $table->integer('strike');
    $table->integer('volume');
    $table->integer('open_interest');
    $table->timestamps();
});
