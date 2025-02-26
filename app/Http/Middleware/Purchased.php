<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Purchased
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->key) {
            return redirect()->route('notification')
                ->with('message', 'Please provide your license key by adding <code style="font-size:0.8rem">?key=yourKeyHere</code> at the end of URL.');
        }

        $keys = json_decode(Storage::get('keys.json'), true);
        if (! $keys || ! $keys['wims']) {
            return redirect()->route('notification')->with('message', 'Installation key not found.');
        } elseif ($keys['wims'] != trim($request->key)) {
            return redirect()->route('notification')->with('message', 'Please provide the correct key.');
        }

        return $next($request);
    }
}
