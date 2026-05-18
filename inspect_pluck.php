<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Salesman;

$ids = User::whereNotNull('salesman_id')->pluck('salesman_id')->toArray();
echo "=== PLUCKED IDS ===\n";
print_r($ids);

$cleanIds = User::whereNotNull('salesman_id')
    ->where('salesman_id', '!=', '')
    ->where('salesman_id', '>', 0)
    ->pluck('salesman_id')
    ->toArray();
    
echo "=== CLEAN PLUCKED IDS ===\n";
print_r($cleanIds);

$salesmenCount = Salesman::count();
echo "Total Salesmen: {$salesmenCount}\n";

$unlinked = Salesman::whereNotIn('id', $cleanIds)->get();
echo "Unlinked Salesmen count with clean pluck: " . $unlinked->count() . "\n";
foreach ($unlinked as $u) {
    echo "- ID: {$u->id} | Name: {$u->name} | Code: {$u->code}\n";
}
