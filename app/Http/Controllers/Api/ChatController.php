<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Handle chat request using the new Gemini Service
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        try {
            $data = $this->geminiService->generateResponse($request->message);
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$reply) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'empty_response',
                    'message' => 'حدث خطأ في معالجة الرد، يرجى المحاولة مرة أخرى.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'reply' => $reply
            ]);

        } catch (\Throwable $e) {
            // تسجيل الخطأ الفني في السجلات للتتبع
            Log::error('AI Chat Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error_type' => 'ai_error',
                'message' => 'عذراً، حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى لاحقاً.',
                'debug_error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
