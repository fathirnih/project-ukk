<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo 'COUNT=' . \App\Models\Buku::count() . PHP_EOL;
foreach (\App\Models\Buku::select('id','cover')->orderBy('id')->take(20)->get() as $b) {
    echo $b->id . '|' . $b->cover . PHP_EOL;
}
?>
