<?php

namespace Modules\Wallet\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Wallet\Models\WalletUser;

class WalletDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = WalletUser::whereDoesntHave('wallet')->get();

        foreach ($users as $user) {
            $user->wallet()->updateOrCreate([]);
        }
    }
}
