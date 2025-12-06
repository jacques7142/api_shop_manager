<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Création des rôles et permissions pour la boutique
     */
    public function run(): void
    {
        // Réinitialise le cache des permissions (important !)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Liste complète des permissions nécessaires dans la boutique
        $permissions = [
            // Produits
            'voir produits',
            'créer produit',
            'modifier produit',
            'supprimer produit',

            // Ventes
            'enregistrer vente',
            'voir ventes',
            'annuler vente',
            'rembourser vente',

            // Clients
            'voir clients',
            'créer client',
            'modifier client',

            // Rapports & Statistiques
            'voir rapports',
            'exporter rapports',

            // Gestion des utilisateurs (réservé à l'admin)
            'gérer utilisateurs',
            'gérer rôles et permissions',
        ];

        // Création des permissions une par une
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Création des rôles
        $admin   = Role::firstOrCreate(['name' => 'admin']);
        $employe = Role::firstOrCreate(['name' => 'employe']);

        // L'admin (gérant) a TOUTES les permissions
        $admin->givePermissionTo(Permission::all());

        // L'employé a seulement les permissions opérationnelles courantes
        $employe->givePermissionTo([
            'voir produits',
            'enregistrer vente',
            'voir ventes',
            'voir clients',
            'créer client',
        ]);

        // Création d'un utilisateur admin par défaut (gérant principal)
        if (!DB::table('users')->where('email', 'admin@boutique.tg')->exists()) {
            $user = \App\Models\User::create([
                'name'              => 'Gérant Principal',
                'email'             => 'admin@boutique.tg',
                'email_verified_at' => now(),
                'password'          => bcrypt('password123'), // Change ce mot de passe après !
            ]);

            $user->assignRole('admin');

            $this->command->info('Utilisateur admin créé : admin@boutique.tg / password123');
        }

        $this->command->info('Rôles et permissions créés avec succès !');
        $this->command->info('Admin → Toutes les permissions');
        $this->command->info('Employé → Opérations courantes uniquement');
    }
}