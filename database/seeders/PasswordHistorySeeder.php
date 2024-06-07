<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PasswordHistory;
use App\Models\Password;
use Illuminate\Support\Facades\Crypt;

class PasswordHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Récupérer quelques mots de passe de la base de données
        $passwords = Password::all()->take(5);

        // Créer des enregistrements de modifications pour chaque mot de passe
        foreach ($passwords as $password) {
            PasswordHistory::create([
                'password_id' => $password->id,
                'old_password' => Crypt::encryptString('ancien_mot_de_passe'),
                'new_password' => Crypt::encryptString('nouveau_mot_de_passe'),
                'changed_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
