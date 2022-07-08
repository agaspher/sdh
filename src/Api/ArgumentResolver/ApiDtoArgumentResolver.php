<?php

declare(strict_types=1);

namespace App\Api\ArgumentResolver;

use App\Api\DTO\Request\Interfaces\ApiRequestDtoInterface;
use App\Api\Exception\RequestNotJsonException;
use App\Api\Exception\RequestValidationApiException;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Utils;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiDtoArgumentResolver implements ArgumentValueResolverInterface
{
    protected ArrayTransformerInterface $arrayTransformer;
    protected ValidatorInterface $validator;

    public function __construct(ArrayTransformerInterface $arrayTransformer, ValidatorInterface $validator)
    {
        $this->arrayTransformer = $arrayTransformer;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (!$argument->getType() || !class_exists($argument->getType())) {
            return false;
        }

        return in_array(ApiRequestDtoInterface::class, class_implements($argument->getType()));
    }

    /**
     * @inheritdoc
     *
     * @throws RequestNotJsonException
     * @throws RequestValidationApiException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $body = $request->getContent();

        $json = [];

        if ($body) {
            try {
                $json = Utils::jsonDecode($body, true);
            } catch (InvalidArgumentException $e) {
                throw new RequestNotJsonException(null, $e);
            }
        }

        $newRequest = $this->arrayTransformer->fromArray(
            $request->isMethodSafe() ? $request->query->all() : $json,
            $argument->getType()
        );

        $errors = $this->validator->validate($newRequest);

        if ($errors->count()) {
            throw new RequestValidationApiException($errors);
        }

        yield $newRequest;
    }
}