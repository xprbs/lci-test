<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public $isSuccess;
    public $msg;
    
    public function __construct($isSuccess, $msg, $resource)
    {
        parent::__construct($resource);
        $this->isSuccess = $isSuccess;
        $this->msg = $msg;
    }

    public function toArray($request)
    {
        return [
            'success' => $this->isSuccess,
            'msg' => $this->msg,
            'data' => $this->resource
        ];
    }
}
