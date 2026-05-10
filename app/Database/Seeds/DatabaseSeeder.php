<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Order matters: users -> regimes/activities -> offers/promos -> wallets
        $this->call('App\\Database\\Seeds\\UserSeeder');
        $this->call('App\\Database\\Seeds\\RegimeSeeder');
        $this->call('App\\Database\\Seeds\\ActivitySeeder');
        $this->call('App\\Database\\Seeds\\OfferSeeder');
        $this->call('App\\Database\\Seeds\\PromoCodeSeeder');
        $this->call('App\\Database\\Seeds\\WalletSeeder');
    }
}
