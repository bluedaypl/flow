<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NovaResourceRedirectController extends Controller
{
    /**
     * Redirect to a Laravel Nova Resource.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  int|null  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, string $resource, ?int $id = null)
    {
        // Nova does not have a named route (uses Vue router) so instead redirect to the router
        // @see https://laracasts.com/discuss/channels/nova/nova-resource-route-redirect?page=1
        // @see https://github.com/laravel/nova-issues/issues/2724
        return redirect()->to(config('nova.path')."/resources/{$resource}/{$id}");
    }
}
