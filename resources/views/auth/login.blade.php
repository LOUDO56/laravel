@extends('layout')
@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Connexion</h2>
    
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('login') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Mot de passe</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white rounded py-2 cursor-pointer hover:bg-blue-700">Se connecter</button>
    </form>
    <p class="mt-4 text-center"><a href="{{ url('register') }}" class="text-blue-500 hover:underline">Pas encore de compte ? S'inscrire</a></p>
</div>
@endsection
