<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\Checkout;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Actions\Tec\PrepareOrder;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\CheckoutResource;
use App\Http\Resources\CheckoutCollection;

class CheckoutController extends Controller
{
    public function create()
    {
        return Inertia::render('Checkout/Form', [
            'contacts'   => Contact::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
        ]);
    }

    public function destroy(Checkout $checkout)
    {
        $checkout->load(['items.item', 'items.unit', 'items.variations']);
        if ($checkout->del()) {
            event(new \App\Events\CheckoutEvent($checkout, 'deleted'));

            return redirect()->route('checkouts.index')->with('message', __choice('action_text', ['record' => 'Checkout', 'action' => 'deleted']));
        }

        return redirect()->route('checkouts.index')->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(Checkout $checkout)
    {
        $checkout->load(['items.item', 'items.unit', 'items.variations']);
        if ($checkout->delP()) {
            // event(new \App\Events\CheckoutEvent($checkout, 'deleted'));

            return redirect()->route('checkouts.index')->with('message', __choice('action_text', ['record' => 'Checkout', 'action' => 'permanently deleted']));
        }

        return redirect()->route('checkouts.index')->with('error', __('The record can not be deleted.'));
    }

    public function edit(Checkout $checkout)
    {
        $this->authorize('update', $checkout);

        return Inertia::render('Checkout/Form', [
            'contacts'   => Contact::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
            'edit'       => new CheckoutResource($checkout->load(['items.variations', 'items.item.variations', 'attachments'])),
        ]);
    }

    public function index(Request $request)
    {
        $filters = $request->all('draft', 'search', 'trashed');

        return Inertia::render('Checkout/Index', [
            'filters'   => $filters,
            'checkouts' => new CheckoutCollection(
                Checkout::with(['contact', 'warehouse', 'user'])->filter($filters)->orderByDesc('id')->paginate()->withQueryString()
            ),
        ]);
    }

    public function restore(Checkout $checkout)
    {
        $checkout->restore();
        $checkout->items->each->restore();
        event(new \App\Events\CheckoutEvent($checkout, 'restored'));

        return back()->with('message', __choice('action_text', ['record' => 'Checkout', 'action' => 'restored']));
    }

    public function show(Request $request, Checkout $checkout)
    {
        $checkout->load(['items.variations', 'items.item:id,code,name,track_quantity,track_weight,photo', 'contact', 'warehouse', 'items.unit:id,code,name', 'user:id,name:username', 'attachments']);

        return $request->json ? $checkout : Inertia::render('Checkout/Show', ['checkout' => new CheckoutResource($checkout)]);
    }

    public function store(CheckoutRequest $request)
    {
        $data = $request->validated();
        $checkout = (new PrepareOrder($data, $request->file('attachments'), new Checkout()))->process()->save();
        event(new \App\Events\CheckoutEvent($checkout, 'created'));

        return redirect()->route('checkouts.index')->with('message', __choice('action_text', ['record' => 'Checkout', 'action' => 'created']));
    }

    public function update(CheckoutRequest $request, Checkout $checkout)
    {
        $this->authorize('update', $checkout);
        $data = $request->validated();
        $original = $checkout->load(['items.item', 'items.unit', 'items.variations'])->replicate();
        $checkout = (new PrepareOrder($data, $request->file('attachments'), $checkout))->process()->save();
        event(new \App\Events\CheckoutEvent($checkout, 'updated', $original));
        session()->flash('message', __choice('action_text', ['record' => 'Checkout', 'action' => 'updated']));

        return $request->listing == 'yes' ? redirect()->route('checkouts.index') : back();
    }
}
