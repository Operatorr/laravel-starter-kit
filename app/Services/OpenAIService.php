<?php
namespace App\Services;

use OpenAI\Client;

class OpenAIService
{
    private Client $client;

    public function __construct()
    {
        $this->client = \OpenAI::factory()
            ->withApiKey(config('services.openrouter.key'))
            ->withBaseUri(config('services.openrouter.host'))
            ->make();
    }
    /**
     * Get a single, blocking completion.
     *
     * @param  array  $messages      Already-formatted chat messages.
     * @param  string|null $systemPrompt  The system prompt to prepend.
     * @param  string|null $model    Override model name (optional).
     * @return string                Assistant reply content.
     */
    public function complete(
        array $messages,
        ?string $systemPrompt = null,
        ?string $model = null
    ): string {
        $messages = $this->prependSystemPrompt($messages, $systemPrompt);

        $response = $this->client->chat()->create([
            'model'    => $model ?? config('services.openrouter.model', 'openai/gpt-4.1-nano'),
            'messages' => $messages,
        ]);

        return $response->choices[0]->message->content;
    }

    /**
     * Stream the assistant’s delta text.
     *
     * @param  array  $messages      Already-formatted chat messages.
     * @param  string|null $systemPrompt  The system prompt to prepend.
     * @param  string|null $model    Override model name (optional).
     * @return \Generator            Yields delta strings as they arrive.
     */
    public function stream(
        array $messages,
        ?string $systemPrompt = null,
        ?string $model = null
    ): \Generator {
        $messages = $this->prependSystemPrompt($messages, $systemPrompt);

        $stream = $this->client->chat()->createStreamed([
            'model'    => $model ?? config('services.openrouter.model', 'openai/gpt-4.1-nano'),
            'messages' => $messages,
        ]);

        foreach ($stream as $response) {
            yield $response->choices[0]->delta->content ?? '';
        }
    }

    public function prompt($message)
    {
        $answer = $this->complete([
            ['role' => 'user', 'content' => $message],
        ]);

        return response()->json([
            'answer' => $answer,
        ]);
    }

    public function listModels(): array
    {
        return $this->client->models()->list()->toArray();
    }

    /**
     * Helper: add a system prompt when provided.
     */
    private function prependSystemPrompt(array $messages, ?string $systemPrompt): array
    {
        if ($systemPrompt !== null && $systemPrompt !== '') {
            array_unshift($messages, [
                'role'    => 'system',
                'content' => $systemPrompt,
            ]);
        }

        return $messages;
    }
}