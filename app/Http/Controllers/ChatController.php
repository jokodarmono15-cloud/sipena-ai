<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\KnowledgeBase;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $isInternal = Auth::check();
        $messages = ChatMessage::where('is_internal', $isInternal);

        if ($isInternal) {
            $messages = $messages->where('user_id', Auth::id());
        }

        $messages = $messages->latest()->paginate(20);

        return view('chat.index', compact('messages', 'isInternal'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $startTime = microtime(true);
        
        $response = $this->aiService->chat(
            $request->message,
            Auth::id(),
            Auth::check()
        );

        $processingTime = microtime(true) - $startTime;

        $chatMessage = ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'response' => $response['text'],
            'ai_provider' => $response['provider'],
            'ai_model' => $response['model'],
            'tokens_used' => $response['tokens'] ?? null,
            'processing_time' => $processingTime,
            'is_internal' => Auth::check(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $chatMessage,
        ]);
    }
}
