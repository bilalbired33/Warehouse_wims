<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ItemCollection;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\StockTrailCollection;

class ItemController extends Controller
{
    public function create()
    {
        return Inertia::render('Item/Form', [
            'units'      => Unit::ofAccount()->base()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'categories' => Category::ofAccount()->with('children')->get(),
        ]);
    }

    public function destroy(Item $item)
    {
        if ($item->del()) {
            return redirect()->route('items.index')->with('message', __choice('action_text', ['record' => 'Item', 'action' => 'deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(Item $item)
    {
        if ($item->delP()) {
            return redirect()->route('items.index')->with('message', __choice('action_text', ['record' => 'Item', 'action' => 'permanently deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function destroyPhoto(Item $item)
    {
        if (Storage::disk('assets')->delete($item->photo)) {
            $item->update(['photo' => null]);

            return back()->with('message', __choice('action_text', ['record' => 'Item photo', 'action' => 'deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function edit(Item $item)
    {
        $item->load(['categories:id,code,name', 'stock']);

        return Inertia::render('Item/Form', [
            'edit'       => new ItemResource($item),
            'units'      => Unit::ofAccount()->base()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'categories' => Category::ofAccount()->with('children')->get(),
        ]);
    }

    public function index(Request $request)
    {
        $filters = $request->all('search', 'trashed', 'category');

        return Inertia::render('Item/Index', [
            'filters'    => $filters,
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'items'      => new ItemCollection(
                Item::with(['categories:id,code,name', 'stock.warehouse'])->filter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function restore(Item $item)
    {
        $item->restore();

        return back()->with('message', __choice('action_text', ['record' => 'Item', 'action' => 'restored']));
    }

    public function show(Request $request, Item $item)
    {
        $item->load(['categories:id,code,name', 'allStock.warehouse:id,code,name']);
        $item->setRelation('stock', $item->allStock->groupBy('warehouse_id'));

        return $request->json ? new ItemResource($item) : Inertia::render('Item/Show', ['item' => new ItemResource($item)]);
    }

    public function store(ItemRequest $request)
    {
        $data = $request->validated();
        Item::create($data)->addVariations()->saveRelations($data);

        return redirect()->route('items.index')->with('message', __choice('action_text', ['record' => 'Item', 'action' => 'created']));
    }

    public function trail(Item $item)
    {
        $item->load('stockTrails');

        return Inertia::render('Item/Trail', [
            'item'   => $item->only('id', 'code', 'name'),
            'trails' => new StockTrailCollection($item->stockTrails()->orderByDesc('id')->paginate()),
        ]);
    }

    public function update(ItemRequest $request, Item $item)
    {
        $data = $request->validated();
        $item->update($data);
        $item->addVariations()->saveRelations($data);
        session()->flash('message', __choice('action_text', ['record' => 'Item', 'action' => 'updated']));

        return $request->listing == 'yes' ? redirect()->route('items.index') : back();
    }
}
