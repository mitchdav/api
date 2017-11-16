<?php

namespace Mitchdav\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../config/microservices/api.php' => config_path('microservices/api.php'),
		], 'config');

		/**
		 * @see https://github.com/webpatser/laravel-uuid/issues/5#issuecomment-275031149
		 */
		Validator::extend('uuid', function ($attribute, $value, $parameters, $validator) {
			return (bool)preg_match('/^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$/', $value);
		}, 'The value :attribute must be a valid UUID.');

		Validator::extend('isodatetime', function ($attribute, $value, $parameters, $validator) {
			$pattern = '/^(\d\d\d\d)(-)?(\d\d)(-)?(\d\d)(T)?(\d\d)(:)?(\d\d)(:)?(\d\d)(\.\d+)?(Z|([+-])(\d\d)(:)?(\d\d))$/';

			return (boolean)preg_match($pattern, $value);
		}, 'The value :attribute must be a valid datetime in ISO 8601 format.');
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/microservices/api.php', 'microservices.api');

		$this->app['router']->aliasMiddleware('tenant', \Mitchdav\API\Middleware\Tenant::class);
	}
}