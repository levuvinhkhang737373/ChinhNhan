<?php

namespace App\Http\Resources\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
            'id' => $this->id,
            'username' => $this->username,
            'userId' => $this->user_id,
            'email' => $this->email,
            'address' => $this->address,
            'fullName' => $this->full_name,
            'provider' => $this->provider,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'dateOfBirth' => $this->dateOfBirth,
            'tenCongTy' => $this->Tencongty,
            'diaChiCongTy' => $this->Diachicongty,
            'maKH' => $this->MaKH,
            'district' => $this->district,
            'ward' => $this->ward,
            'cityProvince' => $this->city_province,
            'status' => $this->status,
            'mStatus' => $this->m_status,
            'accumulatedPoints' => $this->accumulatedPoints,
            'dateJoin' => $this->date_join,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,


        ];
    }
}
