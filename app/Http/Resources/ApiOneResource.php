<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiOneResource extends JsonResource
{
    public $status;
    public $message;

    public function __construct($status = 200, $message = 'success',$resource)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    }

    public function toArray(Request $request)
    {
        return [
            'code'    => $this->status,
            'message' => $this->message,
            'data'    => $this->resource
        ];
    }
}
