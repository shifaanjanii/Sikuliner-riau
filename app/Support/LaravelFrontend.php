<?php
declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/** Optional Laravel bridge. Not loaded by the standalone preview. */
final class LaravelFrontend
{
    public static function make(Request $request, array $data, array $bindings): Frontend
    {
        $routeNames = $bindings['routes'] ?? [];
        $required = ['home', 'catalog', 'about', 'food.show', 'login', 'login.store', 'register',
            'register.store', 'logout', 'admin.home', 'regions.index', 'regions.create',
            'regions.edit', 'regions.store', 'regions.update', 'regions.destroy',
            'foods.index', 'foods.create', 'foods.edit', 'foods.store', 'foods.update', 'foods.destroy'];
        foreach ($required as $key) {
            if (empty($routeNames[$key]) || !Route::has($routeNames[$key])) {
                throw new \LogicException('Bind an existing Laravel route for UI key: ' . $key);
            }
        }
        $errors = $request->session()->get('errors');
        $messages = $errors ? $errors->getBag('default')->messages() : [];
        if ($request->session()->has('error')) {
            $messages['general'] = [$request->session()->get('error')];
        }
        $viewer = $request->user();
        $data = array_replace([
            'errors' => $messages,
            'old' => $request->session()->getOldInput(),
            'status' => $request->session()->get('status'),
        ], $data);
        // Admin capability comes from the backend Gate, never a client-supplied role.
        $data['user'] = $viewer ? [
            'name' => $viewer->name ?: 'Akun saya',
            'is_admin' => !empty($bindings['adminAbility']) && Gate::forUser($viewer)->allows($bindings['adminAbility']),
        ] : null;
        $parameterMap = $bindings['parameterMap'] ?? [];
        return new Frontend($data, [
            'connected' => true,
            'csrf' => csrf_field(),
            'assetBase' => asset('assets'),
            'fieldMap' => $bindings['fields'] ?? [],
            'queryMap' => $bindings['queries'] ?? [],
            'regionPhotoEnabled' => $bindings['regionPhotoEnabled'] ?? false,
            'uploadMaxBytes' => $bindings['uploadMaxBytes'] ?? null,
            'uploadAccept' => $bindings['uploadAccept'] ?? null,
            'updateMethod' => $bindings['updateMethod'] ?? 'PUT',
            'urlResolver' => static function (string $key, array $parameters) use ($routeNames, $parameterMap): string {
                $mapped = [];
                foreach ($parameters as $name => $value) {
                    $mapped[$parameterMap[$key][$name] ?? $name] = $value;
                }
                return route($routeNames[$key], $mapped);
            },
        ]);
    }

    /** Defense in depth for admin rendering; mutation routes still need their existing middleware. */
    public static function admin(Request $request, array $data, array $bindings, string $view, array $variables)
    {
        abort_unless($request->user() && !empty($bindings['adminAbility']), 403);
        Gate::forUser($request->user())->authorize($bindings['adminAbility']);
        return response(self::make($request, $data, $bindings)->render($view, $variables, 'admin'));
    }
}
