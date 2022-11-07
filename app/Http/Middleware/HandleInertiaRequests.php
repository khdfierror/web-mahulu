<?php

namespace App\Http\Middleware;

use App\Navigation;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Kedeka\InertiaNavigation\Facades\Navigation as WebNavigation;
use Kedeka\InertiaNewsFlash\NewsFlash;
use Kedeka\InertiaSetting\Facades\Setting;
use Kedeka\Visitor\Facades\Visitor;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $isDashboard = in_array(\Laravel\Jetstream\Http\Middleware\AuthenticateSession::class, $request->route()->middleware());

        return array_merge(parent::share($request), [
            'navigations' => fn () => value(fn ($user) => $user ? app(Navigation::class)($user) : [], $request->user()),
            ...value(function ($user) {
                if ($user) {
                    return [
                        'settings' => [],
                    ];
                } else {
                    return [];
                }
            }, $request->user()),
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'visitor' => fn () => Visitor::get(),
            'settings' => value(fn ($setting) => [
                ...$setting,
                'site' => value(fn ($site) => [
                    ...$site,
                    'footer' => str_replace('#year', date('Y'), $site['footer'] ?? null),
                ], $setting['site'] ?? []),
            ], Setting::all()),
            'web' => [
                ...($isDashboard ? [] : [
                    'navigations' => WebNavigation::get(),
                ]),
            ],
            'tickers' => app(NewsFlash::class)->data(),
        ]);
    }
}
