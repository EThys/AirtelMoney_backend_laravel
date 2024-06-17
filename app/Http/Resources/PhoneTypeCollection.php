<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PhoneTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    
    public static $wrap = "phoneTypes";
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
