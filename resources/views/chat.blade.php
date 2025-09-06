<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Chat - {{ $conversation->title }}</title>
    <!-- Fuente JetBrains Mono para código -->
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Reset y base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #0a0a0a;
            color: #e4e4e7;
            overflow: hidden;
            position: relative;
        }

        /* Efecto de grid de fondo sutil */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                linear-gradient(rgba(0, 255, 157, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 157, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: -1;
        }

        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 255, 157, 0.2);
            position: relative;
        }

        header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #00ff9d, transparent);
            opacity: 0.5;
        }

        header h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 0 0 20px rgba(0, 255, 157, 0.5);
            letter-spacing: -0.025em;
        }

        header button {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            border: 1px solid rgba(0, 255, 157, 0.3);
            color: #00ff9d;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        header button:hover {
            background: linear-gradient(135deg, #2a2a2a, #3a3a3a);
            box-shadow: 0 0 20px rgba(0, 255, 157, 0.3);
            transform: translateY(-1px);
        }

        header button:hover::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 157, 0.1), transparent);
            animation: shimmer 0.8s ease-in-out;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* Área de mensajes */
        #messages {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            scroll-behavior: smooth;
        }

        #messages::-webkit-scrollbar {
            width: 6px;
        }

        #messages::-webkit-scrollbar-track {
            background: rgba(26, 26, 26, 0.5);
            border-radius: 3px;
        }

        #messages::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #00ff9d, #00cc7a);
            border-radius: 3px;
        }

        #messages::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #00cc7a, #00ff9d);
        }

        /* Mensajes */
        .message {
            max-width: 75%;
            padding: 1.25rem 1.5rem;
            border-radius: 16px;
            word-wrap: break-word;
            line-height: 1.6;
            position: relative;
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .message.user {
            background: linear-gradient(135deg, #00ff9d, #00cc7a);
            color: #0a0a0a;
            align-self: flex-end;
            font-weight: 500;
            box-shadow: 0 8px 32px rgba(0, 255, 157, 0.2);
        }

        .message.ai {
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(20px);
            color: #e4e4e7;
            align-self: flex-start;
            border: 1px solid rgba(0, 255, 157, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Bloques de código mejorados */
        .code-block {
            background: #111111;
            border: 1px solid rgba(0, 255, 157, 0.3);
            border-radius: 12px;
            margin: 1rem 0;
            overflow: hidden;
            position: relative;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .code-header {
            background: linear-gradient(90deg, #1a1a1a, #2a2a2a);
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 255, 157, 0.2);
            font-size: 0.8rem;
            color: #9ca3af;
        }

        .code-language {
            font-weight: 500;
            color: #00ff9d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
        }

        .code-content {
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 0.875rem;
            line-height: 1.6;
            padding: 1.25rem;
            color: #e4e4e7;
            overflow-x: auto;
            tab-size: 2;
            background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .code-content::-webkit-scrollbar {
            height: 8px;
        }

        .code-content::-webkit-scrollbar-track {
            background: rgba(26, 26, 26, 0.5);
            border-radius: 4px;
        }

        .code-content::-webkit-scrollbar-thumb {
            background: rgba(0, 255, 157, 0.4);
            border-radius: 4px;
        }

        .code-content::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 255, 157, 0.6);
        }

        .copy-button {
            background: rgba(0, 255, 157, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 255, 157, 0.3);
            color: #00ff9d;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .copy-button:hover {
            background: rgba(0, 255, 157, 0.2);
            box-shadow: 0 0 15px rgba(0, 255, 157, 0.3);
            transform: translateY(-1px);
        }

        .copy-button.copied {
            background: rgba(0, 255, 157, 0.3);
            color: #ffffff;
            border-color: rgba(0, 255, 157, 0.5);
        }

        /* Formulario de chat */
        #chat-form {
            display: flex;
            padding: 1.5rem 2rem;
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(0, 255, 157, 0.2);
            gap: 1rem;
        }

        #chat-form input {
            flex: 1;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(64, 64, 64, 0.5);
            background: rgba(26, 26, 26, 0.8);
            color: #e4e4e7;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        #chat-form input:focus {
            border-color: #00ff9d;
            box-shadow: 0 0 0 3px rgba(0, 255, 157, 0.1), 0 0 20px rgba(0, 255, 157, 0.2);
            background: rgba(26, 26, 26, 0.95);
        }

        #chat-form input::placeholder {
            color: #6b7280;
        }

        #chat-form button {
            padding: 1rem 2rem;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #00ff9d, #00cc7a);
            color: #0a0a0a;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 255, 157, 0.3);
            position: relative;
            overflow: hidden;
        }

        #chat-form button:hover {
            background: linear-gradient(135deg, #00cc7a, #00b366);
            box-shadow: 0 6px 25px rgba(0, 255, 157, 0.4);
            transform: translateY(-2px);
        }

        #chat-form button:active {
            transform: translateY(0);
        }

        /* Indicador de escritura */
        .typing-indicator {
            display: none;
            align-self: flex-start;
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(20px);
            color: #9ca3af;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 0.5rem;
            font-style: italic;
            border: 1px solid rgba(0, 255, 157, 0.1);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.7;
            }

            50% {
                opacity: 1;
            }
        }

        .typing-dots {
            display: inline-flex;
            gap: 0.25rem;
            margin-left: 0.5rem;
        }

        .typing-dot {
            width: 4px;
            height: 4px;
            background: #00ff9d;
            border-radius: 50%;
            animation: typingDot 1.5s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typingDot {

            0%,
            60%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            30% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Syntax highlighting básico */
        .code-content .keyword {
            color: #ff6b6b;
        }

        .code-content .string {
            color: #4ecdc4;
        }

        .code-content .comment {
            color: #6c7086;
            font-style: italic;
        }

        .code-content .function {
            color: #f9ca24;
        }

        .code-content .number {
            color: #a29bfe;
        }

        /* Responsive */
        @media (max-width: 768px) {

            header,
            #chat-form {
                padding: 1rem;
            }

            #messages {
                padding: 1rem;
            }

            .message {
                max-width: 90%;
                padding: 1rem;
            }
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
        <div class="typing-indicator" id="typing-indicator">IA escribiendo<span>.</span><span>.</span><span>.</span>
        </div>
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
                body: JSON.stringify({
                    sender,
                    content
                })
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
                    model: "claude-sonnet-4"
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
