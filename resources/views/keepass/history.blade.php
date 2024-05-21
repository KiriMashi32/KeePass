@extends('layout')

@section('main')
<x-app-layout>

    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
        <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
            Historique des modifications de mot de passe
        </h1>

        <input type="text" id="searchInput" placeholder="Rechercher" class="border p-2 rounded mt-4 w-full">

        <table class="w-full mt-4 border">
            <thead>
                <tr>
                    <th class="border text-white">Ancien mot de passe</th>
                    <th class="border text-white">Nouveau mot de passe</th>
                    <th class="border text-white">Modifié le</th>
                </tr>
            </thead>
            <tbody>
                @if($history)
                    @foreach ($history as $record)
                        <tr>
                            <td class="border text-white">
                                <span id="old-password-{{$record->id}}-hidden">{{ str_repeat('•', min(8, strlen(Crypt::decryptString($record->old_password)))) }}</span>
                                <span id="old-password-{{$record->id}}-visible" style="display: none;">{{ Crypt::decryptString($record->old_password) }}</span>
                                <button onclick="togglePasswordVisibility('old-password-{{$record->id}}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                    Afficher
                                </button>
                            </td>
                            <td class="border text-white">
                                <span id="new-password-{{$record->id}}-hidden">{{ str_repeat('•', min(8, strlen(Crypt::decryptString($record->new_password)))) }}</span>
                                <span id="new-password-{{$record->id}}-visible" style="display: none;">{{ Crypt::decryptString($record->new_password) }}</span>
                                <button onclick="togglePasswordVisibility('new-password-{{$record->id}}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                    Afficher
                                </button>
                            </td>
                            <td class="border text-white">{{ $record->updated_at->translatedFormat('d F Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="border text-white">Aucun historique de modification de mot de passe trouvé.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</x-app-layout>

<script>
    function togglePasswordVisibility(passwordId) {
        const hiddenElement = document.getElementById(`${passwordId}-hidden`);
        const visibleElement = document.getElementById(`${passwordId}-visible`);

        if (hiddenElement.style.display === 'none') {
            hiddenElement.style.display = 'inline';
            visibleElement.style.display = 'none';
        } else {
            hiddenElement.style.display = 'none';
            visibleElement.style.display = 'inline';
        }
    }

    const searchInput = document.querySelector("#searchInput");
    const rows = document.querySelectorAll("tbody tr");

    searchInput.addEventListener("input", function(event) {
        const searchString = event.target.value.toLowerCase();

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.indexOf(searchString) !== -1) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

@endsection
