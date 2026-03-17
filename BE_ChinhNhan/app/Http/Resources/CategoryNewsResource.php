<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryNewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      $desc = $this->newsCategoryDesc;
        return [
            'id'          => $this->cat_id,
            'cat_code'    => $this->cat_code,
            'parent_id'   => $this->parentid,
            'picture'     => $this->picture ? asset($this->picture) : null,
            'is_default'  => (int) $this->is_default,
            'show_home'   => (int) $this->show_home,
            'focus_order' => $this->focus_order,
            'menu_order'  => $this->menu_order,
            'views'       => $this->views,
            'display'     => (int) $this->display,
            'admin_id'    => $this->adminid,

            
            'detail' => [
                'id'             => $desc?->id,
                'cat_id'         => $desc?->cat_id,
                'cat_name'       => $desc?->cat_name,
                'description'    => $desc?->description,
                'friendly_url'   => $desc?->friendly_url,
                'friendly_title' => $desc?->friendly_title,
                'metakey'        => $desc?->metakey,
                'metadesc'       => $desc?->metadesc,
                'lang'           => $desc?->lang,
            ]
        ];
    }
}
