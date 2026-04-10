#!/bin/bash

# Auth Routes & Views
mkdir -p resources/views/auth
mkdir -p resources/views/schools
mkdir -p resources/views/classrooms
mkdir -p resources/views/contents

# 1. layout.blade.php update -> ensure Alpine and Axios
sed -i 's/<\/head>/    <script src="https:\/\/cdn.tailwindcss.com"><\/script>\n    <script defer src="https:\/\/cdn.jsdelivr.net\/npm\/alpinejs@3.x.x\/dist\/cdn.min.js"><\/script>\n    <script src="https:\/\/cdn.jsdelivr.net\/npm\/axios\/dist\/axios.min.js"><\/script>\n<\/head>/g' resources/views/layout.blade.php

echo "Frontend files generated."
