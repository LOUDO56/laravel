<?php
$layout = file_get_contents('resources/views/layout.blade.php');
$nav = <<<'HTML'
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold">MyApp Elearning</a>
            <div>
                @auth
                    <a href="/dashboard" class="px-3 hover:text-blue-200">Tableau de bord</a>
                    <a href="/subjects" class="px-3 hover:text-blue-200">Matières</a>
                    <form method="POST" action="/logout" class="inline">
                        @csrf
                        <button type="submit" class="px-3 hover:text-blue-200 cursor-pointer">Déconnexion ({{ auth()->user()->name }})</button>
                    </form>
                @else
                    <a href="/login" class="px-3 hover:text-blue-200">Connexion</a>
                    <a href="/register" class="px-3 hover:text-blue-200">Inscription</a>
                @endauth
            </div>
        </div>
    </nav>
HTML;

$layout = str_replace("<body>\n    <main", "<body>\n" . $nav . "\n    <main", $layout);
file_put_contents('resources/views/layout.blade.php', $layout);
