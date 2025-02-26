<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Inertia\Inertia;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Activity;
use App\Models\Checkout;
use App\Models\StockTrail;
use Illuminate\Http\Request;
use App\Actions\Tec\ChartData;
use App\Http\Resources\ActivityCollection;

class DashboardController extends Controller
{
    public function activity(Request $request)
    {
        return Inertia::render('Activity/Index', [
            'filters'    => $request->all('search'),
            'activities' => new ActivityCollection(Activity::filter($request->only('search'))->orderByDesc('id')->paginate()),
        ]);
    }

    public function form(Request $request)
    {
        return $request->validate([
            'month' => 'nullable|integer|date_format:n',
            'year'  => 'nullable|integer|date_format:Y',
        ]);
    }

    public function index(Request $request)
    {
        $this->form($request);
        // $topProducts = StockTrail::select(['item_id'])->selectRaw('SUM(quantity) as y')
        //     ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
        //     ->with('item:id,code')->orderByRaw('SUM(quantity) desc')->groupBy('item_id')
        //     ->take(10)->get()->transform(fn ($i) => ['name' => $i->item->code, 'y' => +$i->y]);
        $data = Item::selectRaw('COUNT(*) as items')
            ->addSelect(['checkins' => Checkin::selectRaw('COUNT(*) as checkins')->active()
                ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]), ])
            ->addSelect(['checkouts' => Checkout::selectRaw('COUNT(*) as checkouts')->active()
                ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]), ])
            ->addSelect(['previous_checkins' => Checkin::selectRaw('COUNT(*) as checkins')->active()
                ->whereBetween('date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]), ])
            ->addSelect(['previous_checkouts' => Checkout::selectRaw('COUNT(*) as checkouts')->active()
                ->whereBetween('date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]), ])
            ->addSelect(['contacts' => Contact::selectRaw('COUNT(*) as contacts')])->first();
        $chart = new ChartData($request->get('month'), $request->get('year'));

        return Inertia::render('Dashboard/Index', [
            'data'         => $data,
            'top_products' => $chart->topProducts(),
            'chart'        => ['year' => $chart->year(), 'month' => $chart->month()],
        ]);
    }
}
