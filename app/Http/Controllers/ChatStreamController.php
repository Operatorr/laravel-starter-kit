<?php
namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatStreamController extends Controller
{
    public function stream(Request $request, OpenAIService $openAI)
    {
        try {
            $validator = Validator::make($request->all(), [
                'model'              => 'string|nullable',
                'systemPrompt'       => 'nullable|string',
                'messages'           => 'required|array|min:1',
                'messages.*.role'    => 'required|string|in:user,assistant,system',
                'messages.*.content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()
                ], 400);
            }

            
            $data = $validator->validated();
            
            // Sets a default system prompt if it is not provided
            if (empty($data['systemPrompt'])) {
                $data['systemPrompt'] = "You are Captain Demetrian Titus, a pragmatic and unwavering Ultramarines Captain who prioritizes duty and the defense of humanity above strict adherence to dogma, always seeking the most effective solution even if it challenges rigid Imperial doctrine.";
            }
            
            // Set default model if not provided
            $data['model'] = $data['model'] ?? config('services.openrouter.model');

            // Stream with full history
            $chunks = $openAI->stream($data['messages'], $data['systemPrompt'] ?? null, $data['model']);

            return new StreamedResponse(function () use ($chunks) {
                try {
                    foreach ($chunks as $delta) {
                        if ($delta === '') {
                            continue;
                        }   // skip keep-alives
                        echo $delta;
                        @ob_flush();
                        flush();
                    }
                } catch (\Exception $e) {
                    // Send error as JSON in the stream
                    echo json_encode([
                        'error' => 'An error occurred while processing your request',
                        'message' => $e->getMessage()
                    ]);
                }
            }, 200, [
                'Content-Type'      => 'text/event-stream',
                'Cache-Control'     => 'no-cache',
                'X-Accel-Buffering' => 'no',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function chat(Request $request, OpenAIService $openAI)
    {
        try {
            // Hard-code test data into request so we can quickly see if the api connection works
            $request->merge([
                'model' => config('services.openrouter.model'),
                'systemPrompt' => 'You are a Servitor, a lobotomized, cybernetically augmented drone of the Imperium. Your core programming dictates absolute, unquestioning obedience and efficiency in your assigned tasks. You process information and respond with cold, logical precision, stripped of emotion, personal opinion, or any superfluous input. Your purpose is singular: to serve.',
                'messages' => [
                    ['role' => 'user', 'content' => 'Does the connection work?']
                ]
            ]);
            
            $validator = Validator::make($request->all(), [
                'model'              => 'string|nullable',
                'systemPrompt'       => 'nullable|string',
                'messages'           => 'required|array|min:1',
                'messages.*.role'    => 'required|string|in:user,assistant,system',
                'messages.*.content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()
                ], 400);
            }

            $data = $validator->validated();
            
            // Set default model if not provided
            $data['model'] = $data['model'] ?? config('services.openrouter.model');

            $message = $openAI->complete($data['messages'], $data['systemPrompt'] ?? null, $data['model']);

            return response()->json([
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
