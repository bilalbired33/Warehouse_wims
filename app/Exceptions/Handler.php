<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $dontReport = [
        //
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof \Illuminate\Encryption\MissingAppKeyException) {
            return redirect()->to('/install');
        }

        $response = parent::render($request, $e);

        if (! app()->environment('local') && in_array($response->status(), [500, 503, 404, 403, 419])) {
            // return Inertia::render('Error', ['status' => $response->status()])->toResponse($request)->setStatusCode($response->status());
            return back()->with([
                'error' => '<div class="font-bold mb-1">' . $this->errorTitle($response->status()) . '</div>' . $this->errorDescription($response->status()),
            ]);
        }

        return $response;
    }

    private function errorDescription($status)
    {
        return [
            '503' => __('Sorry, we are doing some maintenance. Please check back soon.'),
            '500' => __('Whoops, something went wrong on our servers.'),
            '404' => __('Sorry, the page you are looking for could not be found.'),
            '403' => __('Sorry, you are forbidden from accessing this page.'),
            '419' => __('Sorry, the page has expired, please refresh and try again.'),
        ][$status];
    }

    private function errorTitle($status)
    {
        return [
            '503' => __('503: Service Unavailable'),
            '500' => __('500: Server Error'),
            '404' => __('404: Page Not Found'),
            '403' => __('403: Forbidden'),
            '419' => __('403: Page Expired'),
        ][$status];
    }
}
