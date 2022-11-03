<?php

use Sequel\Tablier;

$t = new Tablier('_sys_cron_scheduled');

$t->int('id')->primary();

$t->text('job');

$t->int('run_at', 30)->default(0);
$t->timestamp('created_at')->default();

$t->build();

