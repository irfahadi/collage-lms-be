<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
     //define properti
     public $status;
     public $message;
     public $resource;
 
     /**
      * __construct
      *
      * @param  mixed $status
      * @param  mixed $message
      * @param  mixed $resource
      * @return void
      */
     public function __construct($status, $message, $resource)
     {
         parent::__construct($resource);
         $this->status  = $status;
         $this->message = $message;
     }
     
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code'   => $this->status,
            'message'   => $this->message,
            'data'      => [
                'id' => $this->id,
                'role_id' => $this->role_id,
                'name' => $this->name,
                'email' => $this->email,
                'email_verified_at' => $this->email_verified_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                // Tampilkan role sebagai string nama role saja
                'role' => $this->role->role ?? null,
            ]
        ];
    }
}
