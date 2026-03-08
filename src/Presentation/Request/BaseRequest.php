<?php

declare(strict_types=1);

namespace App\Presentation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseRequest
{
    protected array $data = [];

    public function __construct(
        protected ValidatorInterface $validator,
        protected RequestStack $requestStack
    ) {
        $this->populate();
        $this->validate();
    }

    /**
     * Define validation rules for the request
     */
    abstract protected function rules(): array;

    /**
     * Populate request data from current HTTP request
     */
    protected function populate(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (! $request) {
            throw new BadRequestHttpException('No request found');
        }

        $contentType = $request->headers->get('Content-Type', '');

        // Parse JSON body
        if (str_contains($contentType, 'application/json')) {
            $content = $request->getContent();
            $this->data = json_decode($content, true) ?? [];
        } else {
            // Parse form data
            $this->data = array_merge(
                $request->request->all(),
                $request->query->all()
            );
        }

        // Merge route parameters (like {id})
        $this->data = array_merge($this->data, $request->attributes->get('_route_params', []));
    }

    /**
     * Validate request data against defined rules
     */
    protected function validate(): void
    {
        $rules = $this->rules();

        foreach ($rules as $field => $constraints) {
            if (!isset($this->data[$field])) {
                $this->data[$field] = null;
            }

            $violations = $this->validator->validate($this->data[$field], $constraints);

            if (count($violations) > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[$field][] = $violation->getMessage();
                }

                throw new BadRequestHttpException(json_encode([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]));
            }
        }
    }

    /**
     * Get validated data
     */
    public function validated(): array
    {
        return $this->data;
    }

    /**
     * Get a specific field value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Check if field exists
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Get all data
     */
    public function all(): array
    {
        return $this->data;
    }
}
