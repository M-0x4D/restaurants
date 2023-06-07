<?php

namespace App\Http\Resources;


use App\Models\Term;
use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
{
    protected $term;
    function __construct()
    {
        $this->term = new Term;
        
    }
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        // dd($this->id);
// if($this->name == null) {
//     array_filter((array) $this->term , function ($product){  unset($product['name']);
//     return $product;
// });
// }

// return $this;
        return[
            'id' => $this->id,
            'name' => $this->translation->name,
            'description' => $this->translation->description,
        ];
    }
}
