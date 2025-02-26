<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Actions\Tec\PrepareOrder;
use App\Http\Requests\CheckinRequest;
use App\Http\Resources\CheckinResource;
use App\Http\Resources\CheckinCollection;

class CheckinController extends Controller
{
    public function create()
    {
        return Inertia::render('Checkin/Form', [
            'contacts'   => Contact::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
        ]);
    }

    public function destroy(Checkin $checkin)
    {
        $checkin->load(['items.item', 'items.unit', 'items.variations']);
        if ($checkin->del()) {
            event(new \App\Events\CheckinEvent($checkin, 'deleted'));

            return redirect()->route('checkins.index')->with('message', __choice('action_text', ['record' => 'Checkin', 'action' => 'deleted']));
        }

        return redirect()->route('checkins.index')->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(Checkin $checkin)
    {
        $checkin->load(['items.item', 'items.unit', 'items.variations']);
        if ($checkin->delP()) {
            // event(new \App\Events\CheckinEvent($checkin, 'deleted'));

            return redirect()->route('checkins.index')->with('message', __choice('action_text', ['record' => 'Checkin', 'action' => 'permanently deleted']));
        }

        return redirect()->route('checkins.index')->with('error', __('The record can not be deleted.'));
    }

    public function edit(Checkin $checkin)
    {
        $this->authorize('update', $checkin);

        return Inertia::render('Checkin/Form', [
            'contacts'   => Contact::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'edit'       => new CheckinResource($checkin->load(['items.variations', 'items.item.variations', 'attachments'])),
        ]);
    }

    public function index(Request $request)
    {
        $filters = $request->all('draft', 'search', 'trashed');

        return Inertia::render('Checkin/Index', [
            'filters'  => $filters,
            'checkins' => new CheckinCollection(
                Checkin::with(['contact', 'warehouse', 'user'])->filter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function restore(Checkin $checkin)
    {
        $checkin->restore();
        $checkin->items->each->restore();
        event(new \App\Events\CheckinEvent($checkin, 'restored'));

        return back()->with('message', __choice('action_text', ['record' => 'Checkin', 'action' => 'restored']));
    }

    public function show(Request $request, Checkin $checkin)
    {
        $checkin->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight,photo', 'contact', 'warehouse', 'items.unit:id,code,name', 'user:id,name:username', 'attachments']);

        return $request->json ? $checkin : Inertia::render('Checkin/Show', ['checkin' => new CheckinResource($checkin)]);
    }

    public function store(CheckinRequest $request)
    {
        $data = $request->validated();
        $checkin = (new PrepareOrder($data, $request->file('attachments'), new Checkin()))->process()->save();
        event(new \App\Events\CheckinEvent($checkin, 'created'));

        return redirect()->route('checkins.index')->with('message', __choice('action_text', ['record' => 'Checkin', 'action' => 'created']));
    }

    public function update(CheckinRequest $request, Checkin $checkin)
    {
        $this->authorize('update', $checkin);
        $data = $request->validated();
        $original = $checkin->load(['items.item', 'items.unit', 'items.variations'])->replicate();
        $checkin = (new PrepareOrder($data, $request->file('attachments'), $checkin))->process()->save();
        event(new \App\Events\CheckinEvent($checkin, 'updated', $original));
        session()->flash('message', __choice('action_text', ['record' => 'Checkin', 'action' => 'updated']));

        return $request->listing == 'yes' ? redirect()->route('checkins.index') : back();
    }
}
