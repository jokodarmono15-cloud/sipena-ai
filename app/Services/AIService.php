<?php

namespace App\Services;

use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $provider;
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->provider = config('services.ai.provider', 'openrouter');
        $this->apiKey = config('services.ai.api_key');
        $this->model = config('services.ai.model', 'auto');
    }

    public function chat($message, $userId = null, $isInternal = false)
    {
        try {
            $context = $this->buildContext($message, $userId, $isInternal);
            $systemPrompt = $this->buildSystemPrompt($userId, $isInternal);

            $payload = [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt . '\n\nContext:\n' . $context
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ];

            $response = $this->callAIProvider($payload);

            return [
                'text' => $response['choices'][0]['message']['content'],
                'provider' => $this->provider,
                'model' => $this->model,
                'tokens' => $response['usage']['total_tokens'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            return $this->fallbackResponse();
        }
    }

    private function buildContext($message, $userId, $isInternal)
    {
        $context = "";

        // Semantic search dalam knowledge base
        $relevantKB = KnowledgeBase::where('is_active', true)
            ->whereRaw('MATCH(title, content) AGAINST(? IN BOOLEAN MODE)', [$message])
            ->limit(3)
            ->get();

        if ($relevantKB->count() > 0) {
            $context .= "Knowledge Base:\n";
            foreach ($relevantKB as $kb) {
                $context .= "- " . $kb->title . ": " . substr($kb->content, 0, 200) . "\n";
            }
        }

        return $context;
    }

    private function buildSystemPrompt($userId, $isInternal)
    {
        $prompt = "Anda adalah SIPENA AI Assistant, asisten digital untuk SMK Negeri 5 Tanjungpinang.\n";
        $prompt .= "Anda membantu menjawab pertanyaan tentang absensi guru, WFA, izin, tugas luar, dan kebijakan sekolah.\n";
        $prompt .= "Jawab dalam Bahasa Indonesia yang formal dan profesional.\n";

        if ($isInternal && $userId) {
            $user = \App\Models\User::find($userId);
            if ($user->isTeacher()) {
                $prompt .= "Pengguna adalah guru. Tunjukkan data yang relevan dengan guru ini saja.\n";
            } elseif ($user->isHeadmaster()) {
                $prompt .= "Pengguna adalah kepala sekolah. Anda dapat menampilkan data seluruh guru.\n";
            } else {
                $prompt .= "Pengguna adalah admin. Anda memiliki akses ke semua data sistem.\n";
            }
        }

        return $prompt;
    }

    private function callAIProvider($payload)
    {
        $baseUrl = match($this->provider) {
            'openrouter' => 'https://openrouter.io/api/v1/chat/completions',
            'openai' => 'https://api.openai.com/v1/chat/completions',
            'gemini' => 'https://generativelanguage.googleapis.com/v1beta/openai/',
            default => 'https://openrouter.io/api/v1/chat/completions',
        };

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($baseUrl, $payload);

        if (!$response->successful()) {
            throw new \Exception('AI Provider Error: ' . $response->status());
        }

        return $response->json();
    }

    private function fallbackResponse()
    {
        return [
            'text' => 'Maaf, saya sedang mengalami kesulitan. Silakan coba kembali nanti atau hubungi admin untuk bantuan lebih lanjut.',
            'provider' => 'fallback',
            'model' => 'fallback',
            'tokens' => null,
        ];
    }
}
