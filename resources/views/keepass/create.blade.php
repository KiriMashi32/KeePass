@extends('layout')

@section('main')
    <x-app-layout>
        <div class="p-6 lg:p-12 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700 rounded-lg shadow-md">
            <h1 class="mt-8 text-3xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
                Ajouter un mot de passe
            </h1>


            <form method="post" action="/keepass" class="max-w-md mx-auto space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-200">URL</label>
                    <input type="text" id="url" name="url" placeholder="URL" value="{{ old('url') }}" class="border p-3 w-full rounded-md focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom</label>
                    <input type="text" id="name" name="name" placeholder="Nom" value="{{ old('name') }}" class="border p-3 w-full rounded-md focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="space-y-2">
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" value="{{ old('username') }}" class="border p-3 w-full rounded-md focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="relative space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Mot de passe" value="{{ old('password') }}" class="border p-3 w-full rounded-md focus:border-blue-500 dark:bg-gray-700 dark:text-white pr-12">

                    <button type="button" onclick="generatePassword()" class="absolute right-0 top-2 px-3 py-1 text-sm bg-blue-500 text-white rounded-md">Générer</button>

                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-20 top-2 px-3 py-1 text-sm bg-blue-500 text-white rounded-md">Montrer</button>
                </div>

            <!-- Options pour générer un mot de passe -->
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-white">Options pour générer un mot de passe</label>

                <!-- Longueur du mot de passe -->
                <div class="flex items-center space-x-4">
                    <label for="length" class="text-sm font-medium text-gray-700 dark:text-white">Longueur :</label>
                    <input type="number" id="length" name="length" min="8" max="100" value="8" class="border p-2 w-24 rounded-md focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Options pour les caractères -->
                <div class="space-y-1">
                    <input type="checkbox" id="lowercase" name="lowercase" checked>
                    <label for="lowercase" class="text-sm font-medium text-gray-700 dark:text-white">Minuscules</label><br>

                    <input type="checkbox" id="uppercase" name="uppercase" checked>
                    <label for="uppercase" class="text-sm font-medium text-gray-700 dark:text-white">Majuscules</label><br>

                    <input type="checkbox" id="numbers" name="numbers" checked>
                    <label for="numbers" class="text-sm font-medium text-gray-700 dark:text-white">Chiffres</label><br>

                    <input type="checkbox" id="includeSpecialChars" name="includeSpecialChars" checked>
                    <label for="includeSpecialChars" class="text-sm font-medium text-gray-700 dark:text-white">Inclure Caractères Spéciaux</label><br>

                    <input type="checkbox" id="includeUnicodeChars" name="includeUnicodeChars">
                    <label for="includeUnicodeChars" class="text-sm font-medium text-gray-700 dark:text-white">Demon mode</label><br><br>

                    <label for="excludedChars" class="text-sm font-medium text-gray-700 dark:text-white">Caractères à exclure :</label>
                    <input type="text" id="excludedChars" name="excludedChars" placeholder="Exclure les caractères" class="border p-2 w-full rounded-md focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Valider</button>
            </form>
        </div>

    </x-app-layout>

    <script>
        let includeSpecialChars = true;

        function generatePassword() {
            const passwordInput = document.getElementById('password');
            const length = document.getElementById('length').value;
            const lowercase = document.getElementById('lowercase').checked;
            const uppercase = document.getElementById('uppercase').checked;
            const numbers = document.getElementById('numbers').checked;
            const includeSpecialChars = document.getElementById('includeSpecialChars').checked;
            const includeUnicodeChars = document.getElementById('includeUnicodeChars').checked;
            const excludedChars = document.getElementById('excludedChars').value; // Récupérer les caractères à exclure

            passwordInput.value = '';

            let charset = '';
            if (lowercase) charset += 'abcdefghijklmnopqrstuvwxyz';
            if (uppercase) charset += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if (numbers) charset += '0123456789';
            if (includeSpecialChars) {
                const specialChars = '!@#$%^&*()_+<>?,./;:[]{}-=|';
                charset += specialChars;
            }
            if (includeUnicodeChars) {
                // Ajout des caractères de la table Unicode 0000-0FFF
                for (let i = 0x0000; i <= 0x0FFF; i++) {
                    charset += String.fromCharCode(i);
                }
            }

            // Supprimer les caractères à exclure de la plage de caractères
            for (const char of excludedChars) {
                charset = charset.replace(new RegExp(char, 'g'), '');
            }

            let password = '';
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }

            passwordInput.value = password;
        }





        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        // Fonction pour inverser l'état d'inclusion des caractères spéciaux
        function toggleSpecialChars() {
            includeSpecialChars = !includeSpecialChars;
        }
    </script>



@endsection
