<?php

namespace Mitchdav\API\Http;

use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Mitchdav\API\Transformers\Transformer;

class Controller extends BaseController
{
	use Helpers;

	/**
	 * @var array $withRelationships
	 */
	static protected $withRelationships = [];

	/**
	 * @var int $paginationLength
	 */
	static protected $paginationLength = 25;

	/**
	 * @param Model       $collection
	 * @param Transformer $transformer
	 *
	 * @return \Dingo\Api\Http\Response
	 */
	public function performGetAll($collection, $transformer)
	{
		/** @var Paginator $result */
		$result = $collection->with(self::$withRelationships)
		                     ->paginate(self::$paginationLength);

		return $this->response->paginator($result, $transformer);
	}

	/**
	 * @param Model       $collection
	 * @param mixed       $id
	 * @param Transformer $transformer
	 *
	 * @return \Dingo\Api\Http\Response
	 */
	public function performGetOne($collection, $id, $transformer)
	{
		return $this->response->item($this->resolveOne($collection, $id), $transformer);
	}

	public function created($model, $transformer, $location = NULL)
	{
		if ($model instanceof Collection) {
			$response = $this->response->collection($model, $transformer);
		} else {
			$response = $this->response->item($model, $transformer);
		}

		$response->setStatusCode(201);

		if (!is_null($location)) {
			$response->header('Location', $location);
		}

		return $response;
	}

	public function accepted($model, $transformer, $location = NULL)
	{
		if ($model instanceof Collection) {
			$response = $this->response->collection($model, $transformer);
		} else {
			$response = $this->response->item($model, $transformer);
		}

		$response->setStatusCode(202);

		if (!is_null($location)) {
			$response->header('Location', $location);
		}

		return $response;
	}

	/**
	 * @param Model $collection
	 * @param mixed $id
	 *
	 * @return Model
	 */
	protected function resolveOne($collection, $id)
	{
		$result = $collection->with(self::$withRelationships)
		                     ->findOrFail($id);

		return $result;
	}

	/**
	 * @param BaseRequest $request
	 * @param Request     $validationRequest
	 * @param array       $additionalInput
	 */
	protected function validateRequest(BaseRequest $request, Request $validationRequest, array $additionalInput = [])
	{
		$validator = $this->getValidationFactory()
		                  ->make(array_merge($request->all(), $additionalInput), $validationRequest->rules(), $validationRequest->messages(), $validationRequest->attributes());

		if ($validator->fails()) {
			throw new \Dingo\Api\Exception\ValidationHttpException($validator->failed());
		}
	}

	/**
	 * @param array $array
	 * @param array $rules
	 * @param array $messages
	 * @param array $customAttributes
	 */
	protected function validateArray(array $array, array $rules, array $messages = [], array $customAttributes = [])
	{
		$validator = $this->getValidationFactory()
		                  ->make($array, $rules, $messages, $customAttributes);

		if ($validator->fails()) {
			throw new \Dingo\Api\Exception\ValidationHttpException($validator->failed());
		}
	}
}
