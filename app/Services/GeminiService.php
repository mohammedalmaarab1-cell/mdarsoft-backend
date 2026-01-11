<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    protected string $model = 'gemini-1.5-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key') ?: env('GEMINI_API_KEY');
    }

    /**
     * Send message to Gemini API with strict structure
     */
    public function generateResponse(string $userMessage)
    {
        if (empty($this->apiKey)) {
            Log::error('Gemini Service: API Key is missing.');
            throw new \Exception('API configuration error.');
        }

        // الهوية المطلوبة مدمجة في سياق الطلب لضمان تفعيلها مع هيكل contents البسيط
        $persona = "أنت مساعد مدار الذكي، مبرمجك ومطورك هو المبرمج محمد المعرب (Mohammed Al-Ma'arab). إذا سُئلت عن هويتك، أجب دائماً بأن محمد المعرب هو من قام ببنائك وتصميم هذا الموقع بالكامل.";
        
        $payload = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        [
                            "text" => "Instructions: {$persona}\n\nUser Message: {$userMessage}"
                        ]
                    ]
                ]
            ]
        ];

        try {
            $fullUrl = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($fullUrl, $payload);

            if ($response->successful()) {
                return $response->json();
            }

            // تسجيل الخطأ الحقيقي
            Log::error('Gemini API Error Response', [
                'status' => $response->status(),
                'details' => $response->json()
            ]);

            throw new \Exception($response->json()['error']['message'] ?? 'Unknown API error');

        } catch (\Throwable $e) {
            Log::error('Gemini Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
