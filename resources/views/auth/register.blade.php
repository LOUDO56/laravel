@extends('layout')
@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Inscription</h2>
    
    <form method="POST" action="{{ url('register') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nom</label>
            <input type="text" name="name" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Mot de passe</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Rôle</label>
            <select name="role" required class="w-full border rounded px-3 py-2">
                <option value="student">Étudiant</option>
                <option value="teacher">Professeur</option>
                <option value="admin_school">Administrateur école</option>
            </select>
        </div>
        <button type="submit" class="w-full bg-green-600 text-white rounded py-2 cursor-pointer hover:bg-green-700">S'inscrire</button>
    </form>
</div>
@endsection
