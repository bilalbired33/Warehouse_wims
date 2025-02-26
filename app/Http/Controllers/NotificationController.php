<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Checkin;
use App\Models\Checkout;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Mail\EmailCheckin;
use App\Models\Adjustment;
use App\Mail\EmailCheckout;
use App\Mail\EmailTransfer;
use App\Mail\LowStockAlert;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\EmailAdjustment;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function adjustment(Request $request, Adjustment $adjustment)
    {
        if (demo() && ! Str::contains($request->route()->getName(), 'preview')) {
            return back()->with('error', 'This feature is disabled on demo, please <a href="' . route('notifications.adjustment.preview', ['adjustment' => $adjustment->id]) . '" style="color:blue;" target="_blank">click here to preview</a>.');
        }
        $users = User::role('Super Admin')->get();
        $adjustment->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight', 'warehouse', 'items.unit:id,code,name', 'user:id,name:username']);
        if (Str::contains($request->route()->getName(), 'preview')) {
            return new EmailAdjustment($adjustment, true);
        } elseif ($users->isNotEmpty()) {
            foreach ($users as $user) {
                Mail::to($user->email)->queue(new EmailAdjustment($adjustment));
            }

            return back()->with('message', __('Email has been sent.'));
        }

        return back()->with('error', __('Contact email address is not set.'));
    }

    public function checkin(Request $request, Checkin $checkin)
    {
        if (demo() && ! Str::contains($request->route()->getName(), 'preview')) {
            return back()->with('error', 'This feature is disabled on demo, please <a href="' . route('notifications.checkin.preview', ['checkin' => $checkin->id]) . '" style="color:blue;" target="_blank">click here to preview</a>.');
        }
        $checkin->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight', 'contact', 'warehouse', 'items.unit:id,code,name', 'user:id,name:username,email']);
        if (Str::contains($request->route()->getName(), 'preview')) {
            return new EmailCheckin($checkin, true);
        } elseif ($checkin->contact->email) {
            Mail::to($checkin->contact->email)->bcc(auth()->user()->email)->queue(new EmailCheckin($checkin));

            return back()->with('message', __('Email has been sent.'));
        }

        return back()->with('error', __('Contact email address is not set.'));
    }

    public function checkout(Request $request, Checkout $checkout)
    {
        if (demo() && ! Str::contains($request->route()->getName(), 'preview')) {
            return back()->with('error', 'This feature is disabled on demo, please <a href="' . route('notifications.checkout.preview', ['checkout' => $checkout->id]) . '" style="color:blue;" target="_blank">click here to preview</a>.');
        }
        $checkout->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight', 'contact', 'warehouse', 'items.unit:id,code,name', 'user:id,name:username,email']);
        if (Str::contains($request->route()->getName(), 'preview')) {
            return new EmailCheckout($checkout, true);
        } elseif ($checkout->contact->email) {
            Mail::to($checkout->contact->email)->bcc(auth()->user()->email)->queue(new EmailCheckout($checkout));

            return back()->with('message', __('Email has been sent.'));
        }

        return back()->with('error', __('Contact email address is not set.'));
    }

    public function stock(Request $request)
    {
        if (demo()) {
            return back()->with('error', 'This feature is disabled on demo');
        }
        $warehouses = Warehouse::active()->withCount([
            'stock'          => fn ($q) => $q->whereNull('variation_id'),
            'stock as alert' => fn ($q) => $q->whereNull('variation_id')->whereColumn('alert_quantity', '>=', 'quantity'),
        ])->get();

        if ($warehouses->isNotEmpty()) {
            $users = User::role('Super Admin')->get();
            if ($users->isNotEmpty()) {
                foreach ($users as $user) {
                    return new LowStockAlert($warehouses, false, $user);
                }
            }
        }
    }

    public function transfer(Request $request, Transfer $transfer)
    {
        if (demo() && ! Str::contains($request->route()->getName(), 'preview')) {
            return back()->with('error', 'This feature is disabled on demo, please <a href="' . route('notifications.transfer.preview', ['transfer' => $transfer->id]) . '" style="color:blue;" target="_blank">click here to preview</a>.');
        }
        $transfer->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight', 'fromWarehouse', 'toWarehouse', 'items.unit:id,code,name', 'user:id,name:username']);
        if (Str::contains($request->route()->getName(), 'preview')) {
            return new EmailTransfer($transfer, true);
        } elseif ($transfer->toWarehouse->email) {
            Mail::to($transfer->toWarehouse->email)->bcc(auth()->user()->email)->queue(new EmailTransfer($transfer));

            return back()->with('message', __('Email has been sent.'));
        }

        return back()->with('error', __('Contact email address is not set.'));
    }
}
