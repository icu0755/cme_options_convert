<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
                            'driver'   => 'pgsql',
                            'host'     => 'localhost',
                            'database' => 'cme',
                            'username' => 'teamcity',
                            'password' => 'teamcity',
                            'charset'  => 'utf8',
                            'prefix'   => '',
                            'schema'   => 'public',
                        ]);

$capsule->bootEloquent();
