<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request)
    {
        $langFiles = json_decode(File::get(base_path('lang/languages.json')));

        return array_merge(parent::share($request), [
            'demo'      => demo(),
            'settings'  => get_settings(),
            'languages' => $langFiles->available,
            'flash'     => [
                'error'   => session('error'),
                'message' => session('message'),
            ],
        ]);
    }

    public function version(Request $request)
    {
        return parent::version($request);
    }
}
