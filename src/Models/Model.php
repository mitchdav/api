<?php

namespace Mitchdav\API\Models;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Mitchdav\API\Scopes\CreatedAtScope;

class Model extends BaseModel
{
	use BelongsToTenants;

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new CreatedAtScope());
	}

	/**
	 * Get the tenantColumns for this model.
	 *
	 * @return array
	 */
	public function getTenantColumns()
	{
		return isset($this->tenantColumns) ? $this->tenantColumns : config('microservices.api.tenantColumns');
	}
}