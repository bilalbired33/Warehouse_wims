<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Inertia\Inertia;
use App\Models\Stock;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Checkout;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Models\Adjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Resources\ItemCollection;
use Illuminate\Support\Facades\Storage;
use Tecdiary\Laravel\Attachments\Attachment;

class AjaxController extends Controller
{
    public function alerts()
    {
        $warehouses = Warehouse::withCount([
            'stock'          => fn ($q) => $q->whereNull('variation_id'),
            'stock as alert' => fn ($q) => $q->whereNull('variation_id')->whereColumn('alert_quantity', '>=', 'quantity'),
        ])->get();
        $draft = Warehouse::selectRaw('COUNT(*) as warehouses')
            ->addSelect(['checkins' => Checkin::selectRaw('COUNT(*) as checkins')->draft()])
            ->addSelect(['checkouts' => Checkout::selectRaw('COUNT(*) as checkouts')->draft()])
            ->addSelect(['adjustments' => Adjustment::selectRaw('COUNT(*) as adjustments')->draft()])
            ->addSelect(['transfers' => Transfer::selectRaw('COUNT(*) as transfers')->draft()])->first();

        return response()->json(['warehouses' => $warehouses, 'draft' => $draft]);
    }

    public function contacts(Request $request)
    {
        if ($request->input('id')) {
            return Contact::whereId($request->input('id'))->get();
        }

        return Contact::search($request->input('search'))->take(10)->get();
    }

    public function delete($attachment)
    {
        $attachment = Attachment::findOrFail($attachment);
        $attachment->delete();

        return back()->with('message', __choice('action_text', ['record' => 'Attachment', 'action' => 'deleted']));
    }

    public function download($attachment)
    {
        $attachment = Attachment::findOrFail($attachment);

        return Storage::download($attachment->filepath, $attachment->title);
    }

    public function items(Request $request)
    {
        if ($request->input('id')) {
            return Item::with('variations')->whereId($request->input('id'))->get();
        }

        return Item::with('variations')->search($request->input('search'))->take(10)->get();
    }

    public function language($language)
    {
        $langFiles = collect(json_decode(File::get(base_path('lang/languages.json')))->available)->pluck('value')->all();
        if (! in_array($language, $langFiles)) {
            return back()->with('error', __('Language is not available yet.'));
        }
        app()->setlocale($language);
        session(['language' => $language]);

        return back()->with('message', __('Language has been changed.'));
    }

    public function warehouse(Request $request, Warehouse $warehouse)
    {
        $filters = $request->all('search', 'trashed');

        return Inertia::render('Warehouse/Alerts', [
            'filters'   => $filters,
            'warehouse' => $warehouse,
            'items'     => new ItemCollection(
                Item::where('track_quantity', 1)
                    ->with(['stock' => fn ($q) => $q->where('warehouse_id', $warehouse->id)])
                    ->whereHas(
                        'stock',
                        fn ($q) => $q->where('warehouse_id', $warehouse->id)->whereColumn('alert_quantity', '>=', 'quantity')
                    )->orderBy(
                        Stock::select('quantity')->whereColumn('stocks.item_id', 'items.id')->where('warehouse_id', $warehouse->id)->whereNull('variation_id')
                    )->filter($filters)->paginate()->withQueryString()
            ),
        ]);
    }
}
