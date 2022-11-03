<?php

use Sequel\Tablier;

$t = new Tablier('_sys_sendry_scheduled');

$t->int('id')->primary();

$t->varchar('from', 50);
$t->varchar('recepient', 50);
$t->varchar('subject', 100);
$t->text('contents');

$t->int('order', 2)->default(50);
$t->timestamp('created_at')->default();

$t->build();

