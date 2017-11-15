<?php

namespace Mitchdav\API\Transformers;

use League\Fractal\TransformerAbstract;

class Transformer extends TransformerAbstract
{
	public function verifyItem($item)
	{
		return TRUE;
	}

	public function item($data, $transformer, $resourceKey = NULL)
	{
		if ($data != NULL) {
			try {
				return parent::item($data, $transformer, $resourceKey);
			} catch (\Exception $ex) {
				return parent::null();
			}
		} else {
			return parent::null();
		}
	}

	public function collection($data, $transformer, $resourceKey = NULL)
	{
		if ($data != NULL) {
			try {
				return parent::collection($data, $transformer, $resourceKey);
			} catch (\Exception $ex) {
				return parent::null();
			}
		} else {
			return parent::null();
		}
	}
}