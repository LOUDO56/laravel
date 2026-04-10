<?php

$files = [
    'tests/Feature/ClassroomTest.php',
    'tests/Feature/RbacTest.php',
    'tests/Feature/SchoolTest.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Add LD+JSON headers to postJson
    $content = preg_replace_callback('/->postJson\((.*?), \[((?:[^]]|\[.*?\])+)\]\)/su', function($m) {
        $headers = ",\n             ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json']";
        return '->postJson(' . $m[1] . ', [' . $m[2] . ']' . $headers . ')';
    }, $content);
    
    // Fix hydra:member
    $content = str_replace("['member']", "['hydra:member']", $content);

    file_put_contents($file, $content);
}
echo "Tests patched for JSON-LD\n";
