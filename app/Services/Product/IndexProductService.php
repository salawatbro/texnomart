<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class IndexProductService extends BaseService
{
    public function rules(): array
    {
        return [
            'search'=> 'nullable|string',
            'sort_by' => 'nullable|string|in:name,price,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
        ];
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function execute(array $data): Collection
    {
        return Product::when($data['search'] ?? null, function ($query) use ($data) {
            $query->where('name', 'like', "%{$data['search']}%");
        })->when($data['sort_by'] ?? null, function (Builder $query) use ($data) {
            $query->orderBy($data['sort_by'], $data['sort_order'] ?? 'asc');
        })->get();
    }
}
