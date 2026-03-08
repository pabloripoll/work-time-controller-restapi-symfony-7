<?php

namespace App\Presentation\Request\Master;

use App\Presentation\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMasterRequest extends BaseRequest
{
    protected function rules(): array
    {
        return [
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email()
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(min: 8)
            ],
            'nickname' => [
                new Assert\NotBlank(),
                new Assert\Length(min: 3, max: 64)
            ]
        ];
    }
}
