<?php
$files = glob("app/Models/*.php");

foreach ($files as $file) {
    if (in_array(basename($file), ['User.php', 'School.php'])) {
        // already restored or tracked
        continue;
    }
    
    $content = file_get_contents($file);
    preg_match('/protected\s+\$fillable\s*=\s*\[(.*?)\];/s', $content, $matches);
    if (!$matches) continue;
    
    $fillable = $matches[1];
    preg_match_all("/'([^']+)'/", $fillable, $fields);
    
    $methods = "\n";
    foreach ($fields[1] as $field) {
        $camel = str_replace('_', '', ucwords($field, '_'));
        $methods .= "    public function get{$camel}()\n    {\n        return \$this->{$field};\n    }\n\n";
        $methods .= "    public function set{$camel}(\$value)\n    {\n        \$this->{$field} = \$value;\n        return \$this;\n    }\n\n";
    }
    
    // Strip existing methods to be safe, if we partially re-ran this
    $content = preg_replace('/public function get[A-Z][a-zA-Z0-9_]*\(\)[^}]+}/s', '', $content);
    $content = preg_replace('/public function set[A-Z][a-zA-Z0-9_]*\([^)]+\)[^}]+}/s', '', $content);
    
    // Insert just before the last closing brace
    $pos = strrpos($content, '}');
    if ($pos !== false) {
        $newContent = substr($content, 0, $pos) . rtrim($methods) . "\n}\n";
        file_put_contents($file, $newContent);
    }
}
echo "Models fixed with getters and setters.\n";
