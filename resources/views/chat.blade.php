<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Chat - {{ $conversation->title }}</title>
    <!-- Fuente JetBrains Mono para código -->
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Reset mejorado */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #0a2e1f 0%, #0c3b29 25%, #0a2e1f 50%, #062018 100%);
            color: #e0f2e9;
            overflow: hidden;
        }

        /* Efecto de partículas sutiles de fondo */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(46, 204, 113, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(26, 188, 156, 0.05) 0%, transparent 40%);
            pointer-events: none;
            z-index: -1;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background-color: rgba(6, 32, 24, 0.8);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(46, 204, 113, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #a2f0d0;
            text-shadow: 0 0 5px rgba(46, 204, 113, 0.5);
        }

        header button {
            background: linear-gradient(to right, #1a936f, #1a7360);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        header button:hover {
            background: linear-gradient(to right, #208b6b, #196656);
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.4);
            transform: translateY(-2px);
        }

        #messages {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            scroll-behavior: smooth;
        }

        /* Personalización de la barra de scroll */
        #messages::-webkit-scrollbar {
            width: 6px;
        }

        #messages::-webkit-scrollbar-track {
            background: rgba(6, 32, 24, 0.5);
            border-radius: 10px;
        }

        #messages::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #1a936f, #1a7360);
            border-radius: 10px;
        }

        .message {
            max-width: 80%;
            padding: 1rem 1.2rem;
            border-radius: 18px;
            word-wrap: break-word;
            line-height: 1.5;
            white-space: pre-wrap;
            position: relative;
            animation: fadeIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.user {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: #062018;
            align-self: flex-end;
            border-bottom-right-radius: 6px;
        }

        .message.ai {
            background: rgba(26, 36, 33, 0.8);
            backdrop-filter: blur(10px);
            color: #e0f2e9;
            align-self: flex-start;
            border-bottom-left-radius: 6px;
            border: 1px solid rgba(46, 204, 113, 0.15);
        }

        .message pre {
            background-color: #0d1f18;
            color: #a2f0d0;
            padding: 1rem;
            border-radius: 12px;
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            margin-top: 0.8rem;
            position: relative;
            border: 1px solid rgba(46, 204, 113, 0.2);
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.3);
            line-height: 1.5;
            tab-size: 4;
        }

        .message pre::before {
            content: "🟢";
            position: absolute;
            top: 8px;
            left: 12px;
            font-size: 0.7rem;
        }

        .copy-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(26, 147, 111, 0.3);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(46, 204, 113, 0.3);
            color: #a2f0d0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            background: rgba(26, 147, 111, 0.5);
            box-shadow: 0 0 8px rgba(46, 204, 113, 0.3);
        }

        #chat-form {
            display: flex;
            padding: 1.2rem;
            background-color: rgba(6, 32, 24, 0.8);
            backdrop-filter: blur(8px);
            border-top: 1px solid rgba(46, 204, 113, 0.2);
        }

        #chat-form input {
            flex: 1;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            border: 1px solid rgba(46, 204, 113, 0.3);
            background-color: rgba(13, 31, 24, 0.7);
            color: #e0f2e9;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        #chat-form input:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.2);
        }

        #chat-form input::placeholder {
            color: #5d8c7b;
        }

        #chat-form button {
            margin-left: 0.8rem;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: #062018;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #chat-form button:hover {
            background: linear-gradient(135deg, #27ae60 0%, #219653 100%);
            box-shadow: 0 0 12px rgba(46, 204, 113, 0.4);
            transform: translateY(-2px);
        }

        #chat-form button:active {
            transform: translateY(0);
        }

        /* Indicador de escritura de la IA */
        .typing-indicator {
            display: none;
            align-self: flex-start;
            background-color: rgba(26, 36, 33, 0.8);
            color: #a2f0d0;
            padding: 0.8rem 1.2rem;
            border-radius: 18px;
            margin-bottom: 0.5rem;
            font-style: italic;
        }

        .typing-indicator span {
            display: inline-block;
            animation: bounce 1.5s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
    </style>

    <script src="https://js.puter.com/v2/"></script>
</head>

<body>

    <header>
        <h1>Chat - {{ $conversation->title }}</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Salir</button>
        </form>
    </header>

    <div id="messages">
        @forelse($messages as $message)
        <div class="message {{ $message->sender }}">
            {!! nl2br(e($message->content)) !!}
        </div>
        @empty
        <p>No hay mensajes todavía.</p>
        @endforelse
        <div class="typing-indicator" id="typing-indicator">IA escribiendo<span>.</span><span>.</span><span>.</span></div>
    </div>

    <form id="chat-form">
        @csrf
        <input type="text" id="message" placeholder="Escribe un mensaje..." autocomplete="off" />
        <button type="submit">Enviar</button>
    </form>

    <script>
        const form = document.getElementById("chat-form");
        const input = document.getElementById("message");
        const messagesDiv = document.getElementById("messages");
        const typingIndicator = document.getElementById("typing-indicator");
        const saveUrl = "{{ route('chat.message', $conversation->id) }}";
        const csrf = "{{ csrf_token() }}";

        function appendMessage(sender, text) {
            const div = document.createElement("div");
            div.className = "message " + sender;

            // Detectar bloques de código entre ``` ``` y crear <pre>
            const codeRegex = /```([\s\S]*?)```/g;
            let lastIndex = 0;
            let match;
            let hasContent = false;

            while ((match = codeRegex.exec(text)) !== null) {
                // texto normal antes del código
                if (match.index > lastIndex) {
                    const span = document.createElement("span");
                    span.textContent = text.slice(lastIndex, match.index);
                    div.appendChild(span);
                    hasContent = true;
                }

                // código
                const pre = document.createElement("pre");
                pre.textContent = match[1].trim();

                const btn = document.createElement("button");
                btn.textContent = "Copiar";
                btn.className = "copy-btn";
                btn.onclick = () => {
                    navigator.clipboard.writeText(match[1].trim());
                    btn.textContent = "¡Copiado!";
                    setTimeout(() => {
                        btn.textContent = "Copiar";
                    }, 2000);
                };
                pre.appendChild(btn);

                div.appendChild(pre);
                hasContent = true;
                lastIndex = match.index + match[0].length;
            }

            // resto del texto
            if (lastIndex < text.length) {
                const span = document.createElement("span");
                span.textContent = text.slice(lastIndex);
                div.appendChild(span);
                hasContent = true;
            }

            // Si no había contenido (solo código), agregar un espacio para mantener el diseño
            if (!hasContent) {
                const span = document.createElement("span");
                span.textContent = " ";
                div.appendChild(span);
            }

            messagesDiv.insertBefore(div, typingIndicator);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        async function saveMessage(sender, content) {
            await fetch(saveUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ sender, content })
            });
        }

        form.addEventListener("submit", async e => {
            e.preventDefault();
            const userMessage = input.value.trim();
            if (!userMessage) return;

            appendMessage("user", userMessage);
            await saveMessage("user", userMessage);
            input.value = "";

            // Mostrar indicador de escritura
            typingIndicator.style.display = "block";
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            try {
                const response = await puter.ai.chat(userMessage, {
                    model: "claude-opus-4"
                });

                // Ocultar indicador de escritura
                typingIndicator.style.display = "none";

                const aiMessage = response.message.content[0].text;
                appendMessage("ai", aiMessage);
                await saveMessage("ai", aiMessage);

            } catch (err) {
                console.error("Error con Claude:", err);
                typingIndicator.style.display = "none";
                appendMessage("ai", "❌ Error al contactar con la IA. Por favor, intenta nuevamente.");
            }
        });

        // Enfocar el input al cargar
        window.addEventListener('load', () => {
            input.focus();
        });
    </script>
</body>

</html>
