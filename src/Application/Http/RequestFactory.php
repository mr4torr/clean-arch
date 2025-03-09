<?php

declare(strict_types=1);

namespace App\Application\Http;

use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Exception\FieldException;
use Shared\Http\AbstractRequestFactory;

final class RequestFactory extends AbstractRequestFactory
{
    public function __construct(
        ServerRequestInterface $serverRequest,
        private ValidatorFactoryInterface $validation
    ) {
        parent::__construct($serverRequest);
    }

    public function validate(array $rules, array $messages = []): void {
        $validator = $this->validation->make($this->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new FieldException($validator->errors()->getMessages());
        }
    }
}
