<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Models\Adjustment;
use Illuminate\Http\Request;
use App\Http\Resources\CheckinCollection;
use App\Http\Resources\CheckoutCollection;
use App\Http\Resources\TransferCollection;
use App\Http\Resources\AdjustmentCollection;

class ReportController extends Controller
{
    public function adjustment(Request $request)
    {
        $filters = $request->all('start_date', 'end_date', 'start_created_at', 'end_created_at', 'reference', 'user_id', 'warehouse_id', 'draft', 'trashed', 'category_id');

        return Inertia::render('Report/Adjustment', [
            'filters'     => $filters,
            'users'       => User::ofAccount()->get(),
            'categories'  => Category::ofAccount()->get(),
            'warehouses'  => Warehouse::ofAccount()->active()->get(),
            'adjustments' => new AdjustmentCollection(
                Adjustment::with(['warehouse', 'user'])->reportFilter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function checkin(Request $request)
    {
        $filters = $request->all('start_date', 'end_date', 'start_created_at', 'end_created_at', 'reference', 'contact_id', 'user_id', 'warehouse_id', 'draft', 'trashed', 'category_id');

        return Inertia::render('Report/Checkin', [
            'filters'    => $filters,
            'users'      => User::ofAccount()->get(),
            'contacts'   => Contact::ofAccount()->get(),
            'categories' => Category::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'checkins'   => new CheckinCollection(
                Checkin::with(['contact', 'warehouse', 'user'])->reportFilter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function checkout(Request $request)
    {
        $filters = $request->all('start_date', 'end_date', 'start_created_at', 'end_created_at', 'reference', 'contact_id', 'user_id', 'warehouse_id', 'draft', 'trashed', 'category_id');

        return Inertia::render('Report/Checkout', [
            'filters'    => $filters,
            'users'      => User::ofAccount()->get(),
            'contacts'   => Contact::ofAccount()->get(),
            'categories' => Category::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'checkouts'  => new CheckoutCollection(
                Checkout::with(['contact', 'warehouse', 'user'])->reportFilter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function index()
    {
        $data = Item::selectRaw('COUNT(*) as items')->without('unit')
            ->addSelect(['contacts' => Contact::selectRaw('COUNT(*) as contacts')])
            ->addSelect(['categories' => Category::selectRaw('COUNT(*) as categories')])
            ->addSelect(['warehouses' => Warehouse::selectRaw('COUNT(*) as warehouses')])
            ->addSelect(['checkins' => Checkin::selectRaw('COUNT(*) as checkins')])
            ->addSelect(['checkouts' => Checkout::selectRaw('COUNT(*) as checkouts')])
            ->addSelect(['transfers' => Transfer::selectRaw('COUNT(*) as transfers')])
            ->addSelect(['adjustments' => Adjustment::selectRaw('COUNT(*) as adjustments')])
            ->addSelect(['units' => Unit::selectRaw('COUNT(*) as units')])
            ->addSelect(['users' => User::selectRaw('COUNT(*) as users')])
            ->addSelect(['roles' => Role::selectRaw('COUNT(*) as roles')])
            ->first();

        return Inertia::render('Report/Index', ['data' => $data]);
    }

    public function transfer(Request $request)
    {
        $filters = $request->all('start_date', 'end_date', 'start_created_at', 'end_created_at', 'reference', 'from_warehouse_id', 'user_id', 'to_warehouse_id', 'draft', 'trashed', 'category_id');

        return Inertia::render('Report/Transfer', [
            'filters'    => $filters,
            'users'      => User::ofAccount()->get(),
            'categories' => Category::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'transfers'  => new TransferCollection(
                Transfer::with(['fromWarehouse', 'toWarehouse', 'user'])->reportFilter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }
}
