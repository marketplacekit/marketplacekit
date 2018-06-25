<?php

namespace App\Http\Middleware;

use Closure;

class PushURL
{
    protected $except = [
        'inbox*', '*listing/star*', 'create*'
    ];
	
	/**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }
        return false;
    }

	
    public function handle($request, Closure $next, $guard = null)
    {
		if ($request->isMethod('GET')) {
            if ($this->inExceptArray($request)) {
                return $next($request);
            }
        }
        #dd(__LINE__);

        $response = $next($request);
		$query_params = collect($request->query())->reject(function ($k, $v) {
			return substr( $v, 0, 3 ) === "ic-" || substr( $v, 0, 1 ) === "_";
		});

		$query_string = '';
		if($query_params)
			$query_string = '?'.http_build_query($query_params->toArray());
		#dd($response);
        $response->header('X-IC-PushURL', $request->url().$query_string);

        return $response;
    }
}
