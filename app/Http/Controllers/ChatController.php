<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Lista o redirige a la conversación activa
    public function index()
    {
        $conversation = Conversation::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->first();

        // Si no hay ninguna conversación, crear una y redirigir
        if (! $conversation) {
            $conversation = Conversation::create([
                'user_id' => Auth::id(),
                'title' => 'Nueva conversación',
            ]);
        }

        // Redirige a la vista de una conversación concreta,
        // así la vista siempre recibirá $conversation y $messages
        return redirect()->route('chat', $conversation->id);
    }

    // Muestra una conversación concreta y sus mensajes
    public function show($id)
    {
        $conversation = Conversation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Cargar mensajes ordenados
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return view('chat', compact('conversation', 'messages'));
    }

    // Crea una nueva conversación y redirige
    public function newConversation()
    {
        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'title' => 'Nueva conversación',
        ]);

        return redirect()->route('chat', $conversation->id);
    }

    // Guarda un mensaje (user o ai)
    public function storeMessage(Request $request, $conversationId)
    {
        $conversation = Conversation::where('id', $conversationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'sender' => 'required|in:user,ai',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender' => $validated['sender'],
            'content' => $validated['content'],
        ]);

        // Actualiza updated_at de la conversación
        $conversation->touch();

        return response()->json($message, 201);
    }
}
