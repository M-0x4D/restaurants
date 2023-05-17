<?php

namespace App\Http\Resources\SearchHistory;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchHistoryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'keyword' => $this->keyword,
            'user_id' => $this->user_id,
        ];
    }
}
