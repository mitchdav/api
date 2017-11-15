<?php

namespace Mitchdav\API\Models;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mitchdav\API\Observers\SoftDeleteObserver;
use Mitchdav\API\Scopes\CreatedAtScope;
use Ramsey\Uuid\Uuid;

class Model extends BaseModel
{
	use BelongsToTenants, SoftDeletes;

	public    $incrementing = FALSE;

	protected $dates        = [
		'deleted_at',
	];

	protected $hidden       = [
		'deleted_at',
		'deletion_token',
	];

	protected $guarded      = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		$this->attributes['id'] = Uuid::uuid4()
		                              ->toString();
	}

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		self::addGlobalScope(new CreatedAtScope());
		self::observe(SoftDeleteObserver::class);
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