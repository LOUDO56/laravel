<?php

$files = glob('tests/Feature/*Test.php');

foreach ($files as $file) {
    $content = file_get_contents($file);

    // Replace postJson without headers
    $content = preg_replace(
        "/(->postJson\([^,]+,\s*\[([^\]]+)\])\s*\)/s",
        "$1, ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])",
        $content
    );

    // Replace patchJson without headers
    $content = preg_replace(
        "/(->patchJson\([^,]+,\s*\[([^\]]+)\])\s*\)/s",
        "$1, ['Content-Type' => 'application/merge-patch+json', 'Accept' => 'application/ld+json'])",
        $content
    );

    // Replace deleteJson without headers
    $content = preg_replace(
        "/(->deleteJson\([^\)]+)\)/",
        "$1, [], ['Accept' => 'application/ld+json'])",
        $content
    );

    // Change 'member' to 'hydra:member'
    $content = str_replace("['member']", "['hydra:member']", $content);

    file_put_contents($file, $content);
}
echo "Tests patched.\n";
