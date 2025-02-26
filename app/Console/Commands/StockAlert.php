<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Mail\LowStockAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class StockAlert extends Command
{
    protected $description = 'Send low stock notifications';

    protected $signature = 'stock:alert';

    public function handle()
    {
        Account::all()->each(function ($account) {
            $this->info('Sending low stock alerts');
            $warehouses = $account->warehouses()->withCount([
                'stock'          => fn ($q) => $q->whereNull('variation_id'),
                'stock as alert' => fn ($q) => $q->whereNull('variation_id')->whereColumn('alert_quantity', '>=', 'quantity'),
            ])->active()->get();

            if ($warehouses->isNotEmpty()) {
                foreach ($warehouses as $warehouse) {
                    if ($warehouse->email && $warehouse->alert) {
                        $this->info('Sending email to warehouse ' . $warehouse->code);
                        Mail::to($warehouse->email)->queue(new LowStockAlert($warehouse, true));
                    }
                }
                $users = $account->users()->role('Super Admin')->get();
                if ($users->isNotEmpty()) {
                    foreach ($users as $user) {
                        $this->info('Sending email to user ' . $user->username);
                        Mail::to($user->email)->queue(new LowStockAlert($warehouses, false, $user));
                    }
                }
            }
        });
    }
}
