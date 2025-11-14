<?php

namespace App\Exception;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'kernel.exception', priority: 200)]
class ExceptionListener
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $this->logger->error('MY LISTENER TRIGGERED: ' . $event->getThrowable()::class);


        $exception = $event->getThrowable();

        // Дефолтные значения
        $message = $exception->getMessage();
        $status = 500;
        $errorCode = 'UNKNOWN_ERROR';

        // Если исключение наследуется от HttpException
        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
        }

        // Если наше доменное исключение
        if ($exception instanceof DomainException) {
            $status = $exception->getStatusCode();
            $errorCode = $exception->getDomainCode();
        }

        $response = new JsonResponse([
            'error' => $errorCode,
            'message' => $message,
        ], $status,
        );

        // Для русских букв
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        // Устанавливаем заголовки
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        $event->setResponse($response);
    }
}
