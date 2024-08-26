<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

abstract class BaseService
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validate(array $data): bool
    {
        Validator::make($data, $this->rules())
            ->validate();
        return true;
    }
}
