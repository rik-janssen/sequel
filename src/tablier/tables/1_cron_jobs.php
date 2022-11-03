<?php

use Sequel\Tablier;

$t = new Tablier('_sys_cron');

$t->int('id')->primary();

$t->text('job');

$t->int('interval', 30)->default('86400');
$t->int('last_ran', 12);

$t->build();

