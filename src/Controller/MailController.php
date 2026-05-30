<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class MailController extends AbstractController
{
    #[Route('/mail/test', name: 'mail_test', methods: ['POST'])]
    public function test(Request $request, MailerInterface $mailer): JsonResponse
    {
        $payload = json_decode($request->getContent(), true) ?? [];
        $from = $_ENV['MAIL_FROM'] ?? 'noreply@example.test';

        $body = $payload['body'] ?? $payload['text'] ?? 'Mailexam test from Symfony';

        $mailer->send(
            (new Email())
                ->from($from)
                ->to($payload['to'] ?? 'user@example.test')
                ->subject($payload['subject'] ?? 'Symfony + Mailexam')
                ->text($body)
        );

        return $this->json(['status' => 'ok']);
    }
}
