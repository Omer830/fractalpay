<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Modules\Auth\Enums\CommonKeys;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        InertiaCognito::addUserNameToSession($request->user_name ? $request->user_name : request()->session()->get(CommonKeys::USER_NAME->value));
        return array_merge(parent::share($request), [
            CommonKeys::REFEREE_CODE->value => request()->session()->get(CommonKeys::REFEREE_CODE->value),
            CommonKeys::USER_NAME->value => request()->session()->get(CommonKeys::USER_NAME->value),
        ]);

    }
}
