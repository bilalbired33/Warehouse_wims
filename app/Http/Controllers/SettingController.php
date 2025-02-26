<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Setting;
use App\Rules\LocaleLength;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Setting/Index', [
            'current' => get_settings(),
            'laravel' => app()->version(),
        ]);
    }

    public function store(Request $request)
    {
        $settings = $request->validate([
            'name'           => 'required',
            'currency_code'  => 'required|size:3',
            'weight_unit'    => 'nullable|string',
            'over_selling'   => 'nullable|boolean',
            'track_weight'   => 'nullable|boolean',
            'sidebar'        => 'required|in:full,mini',
            'language'       => 'required|string',
            'sidebar_style'  => 'required|in:full,dropdown',
            'per_page'       => 'required|in:10,15,25,50,100',
            'default_locale' => ['required', new LocaleLength()],
            'fraction'       => 'required|integer|min:0|in:0,1,2,3,4',
            'reference'      => 'required|string|in:ai,ulid,uuid,uniqid',
            'date_format'    => 'required|in:js,php',
        ]);
        collect($settings)->each(function ($value, $key) {
            Setting::updateOrCreate(['tec_key' => $key, 'account_id' => auth()->user()->account_id], ['tec_value' => $value]);
        });
        log_activity(__choice('action_text', ['record' => 'Settings', 'action' => 'saved']), $settings, auth()->user(), 'Setting');

        return back()->with('message', __choice('action_text', ['record' => 'Settings', 'action' => 'saved']));
    }
}
