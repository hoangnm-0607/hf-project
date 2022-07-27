<?php

namespace App\Messenger;

use App\Exception\UnsupportedMessageException;
use App\Message\EndUserBulkCreateMessage;
use App\Message\SqsMessageInterface;
use App\Message\TestMessage;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Envelope;

class SqsJsonMessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];

        $data = json_decode($body, true);
        if (null === $data) {
            throw new MessageDecodingFailedException('Invalid JSON');
        }

        if (isset($data['name'])) {
            $envelope = match ($data['name']) {
                TestMessage::NAME => $this->createTestMessageEnvelope($data),
                EndUserBulkCreateMessage::NAME => $this->createEndUserBulkCreateMessageEnvelope($data),
                default => throw new MessageDecodingFailedException(sprintf('Invalid name "%s"', $data['name'])),
            };
        }
        else {
            throw new MessageDecodingFailedException('Invalid message, no name found: ' . $body);
        }

        return $envelope;
    }

    /**
     * @throws UnsupportedMessageException
     */
    #[ArrayShape(['body' => "false|string", 'headers' => "array"])] public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if ($message instanceof SqsMessageInterface) {
            // recreate what the data originally looked like
            $data = [
                'name' => $message->getName(),
                'data' => $message->getData()
            ];
        } else {
            throw new UnsupportedMessageException('Unsupported message class');
        }

        return [
            'body' => json_encode($data),
            'headers' => [],
        ];
    }

    private function createTestMessageEnvelope(array $data): Envelope
    {
        if (!isset($data['data'])) {
            throw new MessageDecodingFailedException('Missing data key');
        }
        $message = new TestMessage($data['data']);
        return new Envelope($message);

    }

    private function createEndUserBulkCreateMessageEnvelope(array $data): Envelope
    {
        if (!isset($data['data'])) {
            throw new MessageDecodingFailedException('Missing data key');
        }
        $message = new EndUserBulkCreateMessage($data['data']['companyId'], $data['data']['confirmationId'], $data['data']['email']);

        return new Envelope($message);
    }
}
