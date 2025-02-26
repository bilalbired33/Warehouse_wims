<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Warehouse;
use App\Models\Adjustment;
use Illuminate\Http\Request;
use App\Actions\Tec\PrepareOrder;
use App\Http\Requests\AdjustmentRequest;
use App\Http\Resources\AdjustmentResource;
use App\Http\Resources\AdjustmentCollection;

class AdjustmentController extends Controller
{
    public function create()
    {
        return Inertia::render('Adjustment/Form', [
            'warehouses' => Warehouse::ofAccount()->active()->get(),
        ]);
    }

    public function destroy(Adjustment $adjustment)
    {
        $adjustment->load(['items.item', 'items.unit', 'items.variations']);
        if ($adjustment->del()) {
            event(new \App\Events\AdjustmentEvent($adjustment, 'deleted'));

            return redirect()->route('adjustments.index')->with('message', __choice('action_text', ['record' => 'Adjustment', 'action' => 'deleted']));
        }

        return redirect()->route('adjustments.index')->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(Adjustment $adjustment)
    {
        $adjustment->load(['items.item', 'items.unit', 'items.variations']);
        if ($adjustment->delP()) {
            // event(new \App\Events\AdjustmentEvent($adjustment, 'deleted'));

            return redirect()->route('adjustments.index')->with('message', __choice('action_text', ['record' => 'Adjustment', 'action' => 'permanently deleted']));
        }

        return redirect()->route('adjustments.index')->with('error', __('The record can not be deleted.'));
    }

    public function edit(Adjustment $adjustment)
    {
        $this->authorize('update', $adjustment);

        return Inertia::render('Adjustment/Form', [
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'edit'       => new AdjustmentResource($adjustment->load(['items.variations', 'items.item.variations', 'attachments'])),
        ]);
    }

    public function index(Request $request)
    {
        $filters = $request->all('draft', 'search', 'trashed');

        return Inertia::render('Adjustment/Index', [
            'filters'     => $filters,
            'adjustments' => new AdjustmentCollection(
                Adjustment::with(['warehouse', 'user'])->filter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function restore(Adjustment $adjustment)
    {
        $adjustment->restore();
        $adjustment->items->each->restore();
        event(new \App\Events\AdjustmentEvent($adjustment, 'restored'));

        return back()->with('message', __choice('action_text', ['record' => 'Adjustment', 'action' => 'restored']));
    }

    public function show(Request $request, Adjustment $adjustment)
    {
        $adjustment->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight', 'warehouse', 'items.unit:id,code,name', 'user:id,name:username', 'attachments']);

        return $request->json ? $adjustment : Inertia::render('Adjustment/Show', ['adjustment' => new AdjustmentResource($adjustment)]);
    }

    public function store(AdjustmentRequest $request)
    {
        $data = $request->validated();
        $adjustment = (new PrepareOrder($data, $request->file('attachments'), new Adjustment()))->process()->save();
        event(new \App\Events\AdjustmentEvent($adjustment, 'created'));

        return redirect()->route('adjustments.index')->with('message', __choice('action_text', ['record' => 'Adjustment', 'action' => 'created']));
    }

    public function update(AdjustmentRequest $request, Adjustment $adjustment)
    {
        $this->authorize('update', $adjustment);
        $data = $request->validated();
        $original = $adjustment->load(['items.item', 'items.unit', 'items.variations'])->replicate();
        $adjustment = (new PrepareOrder($data, $request->file('attachments'), $adjustment))->process()->save();
        event(new \App\Events\AdjustmentEvent($adjustment, 'updated', $original));
        session()->flash('message', __choice('action_text', ['record' => 'Adjustment', 'action' => 'updated']));

        return $request->listing == 'yes' ? redirect()->route('adjustments.index') : back();
    }
}
