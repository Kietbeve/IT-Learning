<?php
$tables = DB::select('SHOW TABLES LIKE "%exam%"');
foreach ($tables as $table) {
    echo array_values((array)$table)[0] . "\n";
}
