<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexOrderService extends BaseService
{
    public function rules(): array
    {
        return [
            'sort_by' => 'nullable|string|in:price,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function execute(array $data): LengthAwarePaginator
    {
        $this->validate($data);
        return Order::with('items.product', 'payments', 'user')
            ->where('user_id', auth()->id())
            ->when(isset($data['sort_by']), function ($query) use ($data) {
                $query->orderBy($data['sort_by'], $data['sort_order'] ?? 'asc');
            })->paginate(perPage: 10, page: $data['page'] ?? 1);
    }
}
