@extends('layout')

@section('main')
<x-app-layout>

    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
        {{-- <x-application-logo class="block h-12 w-auto" /> --}}

        <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
            Mon Coffre
        </h1>

        <input type="text" id="searchInput" placeholder="Rechercher" class="border p-2 rounded mt-4 w-full">


        <table class="w-full mt-4 border">
            <thead>
                <tr>
                    <th class="border text-white">Nom</th>
                    <th class="border text-white">Nom d'utilisateur</th>
                    <th class="border text-white">Mot de passe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($passwords as $password)
                    <tr>
                        <td class="border text-white">{{$password->name}}</td>
                        <td class="border text-white">{{$password->username}}</td>
                        <!-- Ajouter la classe spécifique à exclure de la recherche -->
                        <td class="border text-white exclude-from-search">
                            <span id="password-{{$password->id}}-hidden">{{ str_repeat('•', min(8, strlen(Crypt::decryptString($password->password)))) }}</span>
                            <span id="password-{{$password->id}}-visible" style="display: none;">{{ Crypt::decryptString($password->password) }}</span>

                            <button onclick="togglePasswordVisibility({{$password->id}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                Afficher
                            </button>

                            <button onclick="copyPassword({{$password->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                Copier
                            </button>
                        </td>
                        <td class="border exclude-from-search">
                            <a href="{{ route('keepass.edit', $password->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </a>
                        </td>
                        <td class="border exclude-from-search">
                            <form action="{{ route('keepass.destroy', $password->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

<script>
    function togglePasswordVisibility(passwordId) {
        const hiddenElement = document.getElementById(`password-${passwordId}-hidden`);
        const visibleElement = document.getElementById(`password-${passwordId}-visible`);

        if (hiddenElement.style.display === 'none') {
            hiddenElement.style.display = 'inline';
            visibleElement.style.display = 'none';
        } else {
            hiddenElement.style.display = 'none';
            visibleElement.style.display = 'inline';
        }
    }

    function copyPassword(passwordId) {
        const passwordText = document.getElementById(`password-${passwordId}-visible`).innerText;

        // Créer un élément de texte temporaire pour copier le mot de passe
        const tempInput = document.createElement('input');
        tempInput.value = passwordText;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Vous pouvez ajouter une notification ou un message pour confirmer que le mot de passe a été copié
        alert('Mot de passe copié !');

        setTimeout(() => {
            clearClipboard();
        }, 15000);
    }

    function clearClipboard() {
        // Créer un élément de texte temporaire pour vider le presse-papiers
        const tempInput = document.createElement('input');
        tempInput.style.opacity = '0'; // Rendre l'élément invisible
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Vous pouvez également informer l'utilisateur que le presse-papiers a été vidé
        console.log('Presse-papiers vidé après 15 secondes.');
    }

    const searchInput = document.querySelector("#searchInput");
    const rows = document.querySelectorAll("tbody tr");

    searchInput.addEventListener("input", function(event) {
        const searchString = event.target.value.toLowerCase();

        rows.forEach(row => {
            // Exclure les lignes contenant la classe "exclude-from-search"
            if (!row.classList.contains("exclude-from-search")) {
                const text = row.innerText.toLowerCase();
                if (text.indexOf(searchString) !== -1) {
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
            }
        });
    });
</script>

@endsection
