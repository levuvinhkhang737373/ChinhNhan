<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            //table News
            "News: {$this->news_id}" => [
                'cat_id'      => $this->cat_id,
                'cat_list'    => $this->cat_list,
                'picture'     => $this->picture,
                'focus'       => $this->focus,
                'display'     => $this->display,
                'focus_order' => $this->focus_order,
                'menu_order'  => $this->menu_order,
                'views'       => $this->views,
                'adminid'     => $this->adminid,
            ],
            //table News_Desc
            'Detail' => [
                'id'             => $this->newsDesc?->id,
                'title'          => $this->newsDesc?->title,
                'short'          => $this->newsDesc?->short,
                'content'        => $this->newsDesc?->description,
                'friendly_url'   => $this->newsDesc?->friendly_url,
                'friendly_title' => $this->newsDesc?->friendly_title,
                'metakey'        => $this->newsDesc?->metakey,
                'metadesc'       => $this->newsDesc?->metadesc,
                'lang'           => $this->newsDesc?->lang,
            ]
        ];
    }
}
