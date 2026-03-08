<?php

namespace App\Presentation\Request\Master;

use App\Presentation\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateMasterProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'nickname' => [
                new Assert\NotBlank(message: 'Nickname is required'),
                new Assert\Length(
                    min: 3,
                    max: 64,
                    minMessage: 'Nickname must be at least {{ limit }} characters',
                    maxMessage: 'Nickname cannot be longer than {{ limit }} characters'
                )
            ],
            'avatar' => [
                new Assert\Optional([
                    new Assert\Url()
                ])
            ],
        ];
    }
}
