<?php

namespace Mitchdav\API\Observers;

use Mitchdav\API\Models\Model;
use Ramsey\Uuid\Uuid;

class SoftDeleteObserver
{
	/**
	 * @param  Model $model
	 *
	 * @return void
	 */
	public function deleting(Model $model)
	{
		$model->deletion_token = Uuid::uuid4();

		$model->save();
	}
}