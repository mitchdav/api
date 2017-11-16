<?php

namespace Mitchdav\API\Middleware;

use Closure;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Tenant
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next, ...$parameters)
	{
		$tenantId = $request->header('x-tenant-id', NULL);

		if ($tenantId !== NULL) {
			Landlord::addTenant('tenant_id', $tenantId);
		} else {
			$hasIgnoreTenantId = in_array('ignore-tenant-id', $parameters, TRUE);

			if (!$hasIgnoreTenantId) {
				throw new BadRequestHttpException('The x-tenant-id header must be sent for this request.');
			}
		}

		$mapping = config('microservices.api.tenants.routeParameters', []);

		foreach ($mapping as $parameterName => $tenantColumn) {
			$parameterValue = $request->route()
			                          ->parameter($parameterName);

			if ($parameterValue) {
				Landlord::addTenant($tenantColumn, $parameterValue);
			}
		}

		return $next($request);
	}
}
