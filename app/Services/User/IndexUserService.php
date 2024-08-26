<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexUserService extends BaseService
{
    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'page' => 'nullable|integer',
        ];
    }

    /**
     * @param array $data
     * @return array|LengthAwarePaginator
     */
    public function execute(array $data): array|LengthAwarePaginator
    {
        $this->validate($data);

        return User::when($data['search'] ?? null, function ($query, $search) {
            return $query->where('name', 'like', "%$search%")->orWhere('phone', 'like', "%$search%");
        })->paginate(10);
    }
}
