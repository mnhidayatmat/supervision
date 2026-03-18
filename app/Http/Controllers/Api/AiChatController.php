<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiConversation;
use App\Models\AiProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function conversations()
    {
        $conversations = Auth::user()->aiConversations()
            ->latest()
            ->take(20)
            ->get();

        return response()->json($conversations);
    }

    public function messages(AiConversation $conversation)
    {
        return response()->json($conversation->messages()->orderBy('created_at')->get());
    }

    public function createConversation(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'student_id' => 'nullable|exists:students,id',
            'context_files' => 'nullable|array',
            'scope' => 'nullable|string',
        ]);

        $conversation = Auth::user()->aiConversations()->create([
            'title' => $validated['title'] ?? 'New Conversation',
            'student_id' => $validated['student_id'] ?? null,
            'context_files' => $validated['context_files'] ?? null,
            'scope' => $validated['scope'] ?? 'general',
        ]);

        return response()->json($conversation);
    }

    public function sendMessage(Request $request, AiConversation $conversation)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        // Save user message
        $conversation->messages()->create([
            'role' => 'user',
            'content' => $validated['content'],
        ]);

        // Get AI response
        $provider = AiProvider::where('is_default', true)->where('is_active', true)->first();

        if (!$provider) {
            $conversation->messages()->create([
                'role' => 'assistant',
                'content' => 'AI is not configured. Please ask your admin to set up an AI provider.',
            ]);

            return response()->json([
                'message' => 'AI is not configured.',
                'conversation' => $conversation->load('messages'),
            ]);
        }

        $history = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->toArray();

        try {
            $response = $this->callAiProvider($provider, $history);

            $conversation->messages()->create([
                'role' => 'assistant',
                'content' => $response,
                'metadata' => ['provider' => $provider->slug, 'model' => $provider->model],
            ]);
        } catch (\Exception $e) {
            $conversation->messages()->create([
                'role' => 'assistant',
                'content' => 'Sorry, I encountered an error. Please try again.',
                'metadata' => ['error' => $e->getMessage()],
            ]);
        }

        return response()->json($conversation->load('messages'));
    }

    private function callAiProvider(AiProvider $provider, array $messages): string
    {
        $systemPrompt = 'You are ResearchFlow AI, an academic research assistant. Help with research planning, writing, methodology, and analysis. Be concise and academic.';

        return match ($provider->slug) {
            'openai' => $this->callOpenAi($provider, $messages, $systemPrompt),
            'gemini' => $this->callGemini($provider, $messages, $systemPrompt),
            default => $this->callGenericOpenAiCompatible($provider, $messages, $systemPrompt),
        };
    }

    private function callOpenAi(AiProvider $provider, array $messages, string $systemPrompt): string
    {
        $response = Http::withToken($provider->api_key)
            ->post($provider->base_url ?: 'https://api.openai.com/v1/chat/completions', [
                'model' => $provider->model ?: 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ...$messages,
                ],
                'max_tokens' => 2000,
            ]);

        return $response->json('choices.0.message.content', 'No response received.');
    }

    private function callGemini(AiProvider $provider, array $messages, string $systemPrompt): string
    {
        $contents = collect($messages)->map(fn($m) => [
            'role' => $m['role'] === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $m['content']]],
        ])->toArray();

        $response = Http::post(
            ($provider->base_url ?: 'https://generativelanguage.googleapis.com/v1beta/models/' . ($provider->model ?: 'gemini-pro')) . ':generateContent?key=' . $provider->api_key,
            [
                'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents' => $contents,
            ]
        );

        return $response->json('candidates.0.content.parts.0.text', 'No response received.');
    }

    private function callGenericOpenAiCompatible(AiProvider $provider, array $messages, string $systemPrompt): string
    {
        $response = Http::withToken($provider->api_key)
            ->post(rtrim($provider->base_url, '/') . '/chat/completions', [
                'model' => $provider->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ...$messages,
                ],
                'max_tokens' => 2000,
            ]);

        return $response->json('choices.0.message.content', 'No response received.');
    }
}
