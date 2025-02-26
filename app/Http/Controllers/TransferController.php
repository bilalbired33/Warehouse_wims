<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transfer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Actions\Tec\PrepareOrder;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransferResource;
use App\Http\Resources\TransferCollection;

class TransferController extends Controller
{
    public function create()
    {
        return Inertia::render('Transfer/Form', [
            'warehouses' => Warehouse::ofAccount()->active()->get(),
        ]);
    }

    public function destroy(Transfer $transfer)
    {
        $transfer->load(['items.item', 'items.unit', 'items.variations']);
        if ($transfer->del()) {
            event(new \App\Events\TransferEvent($transfer, 'deleted'));

            return redirect()->route('transfers.index')->with('message', __choice('action_text', ['record' => 'Transfer', 'action' => 'deleted']));
        }

        return redirect()->route('transfers.index')->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(Transfer $transfer)
    {
        $transfer->load(['items.item', 'items.unit', 'items.variations']);
        if ($transfer->delP()) {
            // event(new \App\Events\TransferEvent($transfer, 'deleted'));

            return redirect()->route('transfers.index')->with('message', __choice('action_text', ['record' => 'Transfer', 'action' => 'permanently deleted']));
        }

        return redirect()->route('transfers.index')->with('error', __('The record can not be deleted.'));
    }

    public function edit(Transfer $transfer)
    {
        $this->authorize('update', $transfer);

        return Inertia::render('Transfer/Form', [
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'edit'       => new TransferResource($transfer->load(['items.variations', 'items.item.variations', 'attachments'])),
        ]);
    }

    public function index(Request $request)
    {
        $filters = $request->all('draft', 'search', 'trashed');

        return Inertia::render('Transfer/Index', [
            'filters'   => $filters,
            'transfers' => new TransferCollection(
                Transfer::with(['fromWarehouse', 'toWarehouse', 'user'])->filter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function restore(Transfer $transfer)
    {
        $transfer->restore();
        $transfer->items->each->restore();
        event(new \App\Events\TransferEvent($transfer, 'restored'));

        return back()->with('message', __choice('action_text', ['record' => 'Transfer', 'action' => 'restored']));
    }

    public function show(Request $request, Transfer $transfer)
    {
        $transfer->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight', 'fromWarehouse', 'toWarehouse', 'items.unit:id,code,name', 'user:id,name:username', 'attachments']);

        return $request->json ? $transfer : Inertia::render('Transfer/Show', ['transfer' => new TransferResource($transfer)]);
    }

    public function store(TransferRequest $request)
    {
        $data = $request->validated();
        $transfer = (new PrepareOrder($data, $request->file('attachments'), new Transfer()))->process()->save();
        event(new \App\Events\TransferEvent($transfer, 'created'));

        return redirect()->route('transfers.index')->with('message', __choice('action_text', ['record' => 'Transfer', 'action' => 'created']));
    }

    public function update(TransferRequest $request, Transfer $transfer)
    {
        $this->authorize('update', $transfer);
        $data = $request->validated();
        $original = $transfer->load(['items.item', 'items.unit', 'items.variations'])->replicate();
        $transfer = (new PrepareOrder($data, $request->file('attachments'), $transfer))->process()->save();
        event(new \App\Events\TransferEvent($transfer, 'updated', $original));
        session()->flash('message', __choice('action_text', ['record' => 'Transfer', 'action' => 'updated']));

        return $request->listing == 'yes' ? redirect()->route('transfers.index') : back();
    }
}
