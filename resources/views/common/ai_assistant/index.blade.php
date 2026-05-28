<x-layouts.admin>
    <x-slot name="title">
        AI Financial Assistant
    </x-slot>

    <x-slot name="favorite"
        title="AI Assistant"
        icon="psychology"
        route="ai.index"
    ></x-slot>

    <x-slot name="content">
        <!-- Custom Styles -->
        <style>
            .ai-container {
                display: grid;
                grid-template-columns: 1.2fr 1fr;
                gap: 24px;
                margin-top: 16px;
            }
            @media (max-width: 1024px) {
                .ai-container {
                    grid-template-columns: 1fr;
                }
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                border-radius: 16px;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .dark .glass-card {
                background: rgba(30, 30, 40, 0.7);
                border: 1px solid rgba(255, 255, 255, 0.05);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
            }
            .glass-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.08);
            }
            .gradient-text {
                background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .gradient-btn {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                color: #fff !important;
                border: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.3);
            }
            .gradient-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.4);
                opacity: 0.95;
            }
            /* Dropzone styles */
            .dropzone-area {
                border: 2px dashed #a855f7;
                background: rgba(168, 85, 247, 0.02);
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .dropzone-area:hover, .dropzone-area.dragover {
                background: rgba(168, 85, 247, 0.07);
                border-color: #6366f1;
            }
            /* Chat message styles */
            .chat-bubble-user {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                color: white;
                border-bottom-right-radius: 4px;
            }
            .chat-bubble-ai {
                background: rgba(243, 244, 246, 0.85);
                color: #1f2937;
                border-bottom-left-radius: 4px;
                border: 1px solid rgba(0, 0, 0, 0.05);
            }
            .dark .chat-bubble-ai {
                background: rgba(45, 45, 60, 0.85);
                color: #f3f4f6;
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            /* Pulse typing indicator */
            .typing-indicator span {
                height: 8px;
                width: 8px;
                float: left;
                margin: 0 2px;
                background-color: #9ca3af;
                border-radius: 50%;
                animation: bounce 1.3s infinite both;
            }
            .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
            .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
            @keyframes bounce {
                0%, 80%, 100% { transform: scale(0); }
                40% { transform: scale(1.0); }
            }
            /* Timeline animations */
            .pulse-ring {
                border: 3px solid #a855f7;
                border-radius: 30px;
                height: 14px;
                width: 14px;
                animation: pulsate 1.5s ease-out infinite;
                opacity: 0.0;
            }
            @keyframes pulsate {
                0% { transform: scale(0.1, 0.1); opacity: 0.0; }
                50% { opacity: 1.0; }
                100% { transform: scale(1.2, 1.2); opacity: 0.0; }
            }
        </style>

        <div class="ai-container">
            <!-- Left Side: Dropzone, Live Progress, & History -->
            <div class="flex flex-col gap-6">
                <!-- Dropzone Card -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2 flex items-center gap-2">
                        <span class="material-icons text-purple-600">cloud_upload</span>
                        Upload Documents
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Drag and drop receipts, supplier bills, customer invoices, or bank statements here. Our autonomous AI agent will instantly extract transaction details, create contacts, and auto-bookkeep them.
                    </p>

                    <div id="dropzone" class="dropzone-area rounded-xl p-8 flex flex-col items-center justify-center text-center">
                        <input type="file" id="fileInput" class="hidden" accept=".pdf,.png,.jpg,.jpeg">
                        <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mb-3">
                            <span class="material-icons text-3xl text-purple-600">upload_file</span>
                        </div>
                        <p class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Drag files here or <span class="text-purple-600 underline">browse</span></p>
                        <p class="text-xs text-gray-400">PDF, PNG, JPG or JPEG (Max 10MB)</p>
                    </div>

                    <!-- Live Progress Container -->
                    <div id="progressContainer" class="hidden mt-6 p-4 rounded-xl bg-purple-50 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/30">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-purple-700 dark:text-purple-300 flex items-center gap-2">
                                <span class="animate-spin inline-block w-4 h-4 border-2 border-purple-600 border-t-transparent rounded-full"></span>
                                Processing Document...
                            </span>
                            <span id="progressPercent" class="text-sm font-bold text-purple-700 dark:text-purple-300">15%</span>
                        </div>
                        <div class="w-full bg-purple-200 dark:bg-purple-900/40 rounded-full h-2 mb-4">
                            <div id="progressBar" class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: 15%"></div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div id="step-upload" class="flex items-center gap-2 text-xs text-purple-700 dark:text-purple-300 font-semibold">
                                <span class="material-icons text-sm text-green-500">check_circle</span>
                                File upload complete
                            </div>
                            <div id="step-parse" class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="material-icons text-sm">radio_button_unchecked</span>
                                Reading & extracting data via Multimodal AI
                            </div>
                            <div id="step-bookkeep" class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="material-icons text-sm">radio_button_unchecked</span>
                                Registering contacts, documents, and payments
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Table Card -->
                <div class="glass-card p-6 flex-1">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="material-icons text-purple-600">history</span>
                        Processing History
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800 text-xs text-gray-400 uppercase font-semibold">
                                    <th class="py-3 px-2">File</th>
                                    <th class="py-3 px-2">Status</th>
                                    <th class="py-3 px-2">Type</th>
                                    <th class="py-3 px-2">Bookkept Entry</th>
                                    <th class="py-3 px-2 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                @forelse($uploads as $upload)
                                    <tr class="border-b border-gray-100 dark:border-gray-900 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="py-3 px-2 max-w-xs truncate" title="{{ basename($upload->file_path) }}">
                                            {{ basename($upload->file_path) }}
                                        </td>
                                        <td class="py-3 px-2">
                                            @if($upload->status === 'completed')
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400 flex items-center gap-1 w-max">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Completed
                                                </span>
                                            @elseif($upload->status === 'failed')
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400 flex items-center gap-1 w-max" title="{{ $upload->error_message }}">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Failed
                                                </span>
                                            @elseif($upload->status === 'processing')
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400 flex items-center gap-1 w-max">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span> Processing
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 flex items-center gap-1 w-max">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Uploaded
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-2 capitalize text-xs">
                                            {{ $upload->document_type ?? '—' }}
                                        </td>
                                        <td class="py-3 px-2">
                                            @if($upload->document)
                                                @if($upload->document->type === 'invoice')
                                                    <a href="{{ route('invoices.show', $upload->document->id) }}" class="text-purple-600 hover:underline font-medium">
                                                        Invoice #{{ $upload->document->document_number }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('bills.show', $upload->document->id) }}" class="text-purple-600 hover:underline font-medium">
                                                        Bill #{{ $upload->document->document_number }}
                                                    </a>
                                                @endif
                                            @elseif($upload->transaction)
                                                <a href="{{ route('transactions.edit', $upload->transaction->id) }}" class="text-purple-600 hover:underline font-medium">
                                                    Payment #{{ $upload->transaction->id }}
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="py-3 px-2 text-right text-xs text-gray-400">
                                            {{ $upload->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-400">
                                            <span class="material-icons text-4xl mb-2 text-gray-300 block">inbox</span>
                                            No documents uploaded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Interactive AI Assistant Chat -->
            <div class="glass-card p-6 flex flex-col h-[650px]">
                <div class="border-b border-gray-100 dark:border-gray-800 pb-4 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md">
                        <span class="material-icons text-xl">psychology</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Apex AI Assistant</h3>
                        <p class="text-xs text-green-500 flex items-center gap-1 font-semibold">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-ping"></span> Autonomous Agent Online
                        </p>
                    </div>
                </div>

                <!-- Chat Log -->
                <div id="chatLog" class="flex-1 overflow-y-auto pr-2 flex flex-col gap-4 mb-4">
                    <!-- Default Greeting -->
                    <div class="flex gap-2.5 items-start max-w-[85%]">
                        <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-950 flex items-center justify-center flex-shrink-0 text-purple-600">
                            <span class="material-icons text-sm">smart_toy</span>
                        </div>
                        <div class="chat-bubble-ai p-3 rounded-2xl text-sm leading-relaxed">
                            Hello! I am your <strong>Apex AI Assistant</strong>. I have real-time access to your sales invoices, purchasing bills, bank transactions, and ledger summaries.
                            <br><br>
                            How can I help you today? You can ask me questions like:
                            <ul class="list-disc list-inside mt-2 text-xs flex flex-col gap-1 text-gray-500 dark:text-gray-400">
                                <li>"What is my net cash profit this month?"</li>
                                <li>"How much money is pending to be received from customers?"</li>
                                <li>"Summarize my expenses by type"</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Chat Input Form -->
                <form id="chatForm" class="flex gap-2">
                    <input type="text" id="chatInput" autocomplete="off" placeholder="Ask your AI Assistant a financial question..." class="flex-1 rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-purple-500 focus:border-purple-500 p-3 shadow-inner">
                    <button type="submit" class="gradient-btn px-4 py-3 rounded-xl flex items-center justify-center">
                        <span class="material-icons">send</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    @push('body_js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('fileInput');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const stepUpload = document.getElementById('step-upload');
            const stepParse = document.getElementById('step-parse');
            const stepBookkeep = document.getElementById('step-bookkeep');
            const historyTableBody = document.getElementById('historyTableBody');
            const chatLog = document.getElementById('chatLog');
            const chatForm = document.getElementById('chatForm');
            const chatInput = document.getElementById('chatInput');

            // --- DROPZONE EVENT LISTENERS ---
            dropzone.addEventListener('click', () => fileInput.click());

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    uploadFile(e.dataTransfer.files[0]);
                }
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    uploadFile(fileInput.files[0]);
                }
            });

            // --- FILE UPLOADER HANDLER ---
            function uploadFile(file) {
                // Reset progress UI
                progressContainer.classList.remove('hidden');
                progressBar.style.width = '15%';
                progressBar.classList.remove('bg-red-500');
                progressBar.classList.add('bg-purple-600');
                progressPercent.textContent = '15%';
                stepUpload.className = "flex items-center gap-2 text-xs text-purple-700 dark:text-purple-300 font-semibold";
                stepUpload.innerHTML = `<span class="material-icons text-sm text-green-500">check_circle</span> File upload complete`;
                
                stepParse.className = "flex items-center gap-2 text-xs text-gray-400";
                stepParse.innerHTML = `<span class="material-icons text-sm animate-spin text-purple-600">sync</span> Reading & extracting data via Multimodal AI`;
                
                stepBookkeep.className = "flex items-center gap-2 text-xs text-gray-400";
                stepBookkeep.innerHTML = `<span class="material-icons text-sm">radio_button_unchecked</span> Registering contacts, documents, and payments`;

                let formData = new FormData();
                formData.append('document', file);

                fetch("{{ route('ai.upload') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Start polling progress of the uploaded model ID
                        pollUploadStatus(data.data.id);
                    } else {
                        showUploadError(data.message || 'Upload failed');
                    }
                })
                .catch(err => {
                    showUploadError(err.message || 'Upload failed');
                });
            }

            function showUploadError(msg) {
                stepParse.className = "flex items-center gap-2 text-xs text-red-600";
                stepParse.innerHTML = `<span class="material-icons text-sm text-red-500">cancel</span> Processing failed: ${msg}`;
                progressBar.classList.add('bg-red-500');
                progressBar.classList.remove('bg-purple-600');
            }

            // --- PROGRESS POLLING ---
            let pollInterval;
            function pollUploadStatus(uploadId) {
                if (pollInterval) clearInterval(pollInterval);

                pollInterval = setInterval(() => {
                    fetch("{{ route('ai.history') }}")
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            // Find the active upload in history
                            const active = res.data.find(u => u.id === uploadId);
                            updateHistoryTable(res.data);

                            if (!active) {
                                clearInterval(pollInterval);
                                return;
                            }

                            if (active.status === 'processing') {
                                progressBar.style.width = '50%';
                                progressPercent.textContent = '50%';
                                stepParse.className = "flex items-center gap-2 text-xs text-purple-700 dark:text-purple-300 font-semibold";
                                stepParse.innerHTML = `<span class="material-icons text-sm text-green-500">check_circle</span> Data extracted successfully`;
                                
                                stepBookkeep.className = "flex items-center gap-2 text-xs text-gray-400";
                                stepBookkeep.innerHTML = `<span class="material-icons text-sm animate-spin text-purple-600">sync</span> Registering contacts, documents, and payments`;
                            } 
                            else if (active.status === 'completed') {
                                clearInterval(pollInterval);
                                progressBar.style.width = '100%';
                                progressPercent.textContent = '100%';
                                
                                stepParse.className = "flex items-center gap-2 text-xs text-purple-700 dark:text-purple-300 font-semibold";
                                stepParse.innerHTML = `<span class="material-icons text-sm text-green-500">check_circle</span> Data extracted successfully`;

                                stepBookkeep.className = "flex items-center gap-2 text-xs text-purple-700 dark:text-purple-300 font-semibold";
                                stepBookkeep.innerHTML = `<span class="material-icons text-sm text-green-500">check_circle</span> Document bookkept successfully!`;
                                
                                setTimeout(() => {
                                    progressContainer.classList.add('hidden');
                                }, 5000);
                            } 
                            else if (active.status === 'failed') {
                                clearInterval(pollInterval);
                                showUploadError(active.error_message || 'Processing error occurred');
                            }
                        }
                    })
                    .catch(() => clearInterval(pollInterval));
                }, 3000);
            }

            // --- UPDATE HISTORY TABLE ---
            function updateHistoryTable(uploads) {
                if (!uploads.length) return;

                let html = '';
                uploads.forEach(upload => {
                    let statusBadge = '';
                    if (upload.status === 'completed') {
                        statusBadge = `<span class="px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400 flex items-center gap-1 w-max"><span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Completed</span>`;
                    } else if (upload.status === 'failed') {
                        statusBadge = `<span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400 flex items-center gap-1 w-max" title="${upload.error_message || ''}"><span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Failed</span>`;
                    } else if (upload.status === 'processing') {
                        statusBadge = `<span class="px-2 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400 flex items-center gap-1 w-max"><span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span> Processing</span>`;
                    } else {
                        statusBadge = `<span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 flex items-center gap-1 w-max"><span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Uploaded</span>`;
                    }

                    // Extract file name
                    let fileName = upload.file_path.split(/[\\/]/).pop();

                    let type = upload.document_type || '—';
                    let link = '—';
                    if (upload.document) {
                        let showRoute = upload.document.type === 'invoice' ? `/admin/sales/invoices/${upload.document.id}` : `/admin/purchases/bills/${upload.document.id}`;
                        let docNum = upload.document.document_number;
                        link = `<a href="${showRoute}" class="text-purple-600 hover:underline font-medium">${upload.document.type === 'invoice' ? 'Invoice' : 'Bill'} #${docNum}</a>`;
                    } else if (upload.transaction) {
                        link = `<a href="/admin/banking/transactions/${upload.transaction.id}/edit" class="text-purple-600 hover:underline font-medium">Payment #${upload.transaction.id}</a>`;
                    }

                    // simple human readable date
                    let dateStr = new Date(upload.created_at).toLocaleDateString() + ' ' + new Date(upload.created_at).toLocaleTimeString();

                    html += `
                        <tr class="border-b border-gray-100 dark:border-gray-900 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="py-3 px-2 max-w-xs truncate" title="${fileName}">${fileName}</td>
                            <td class="py-3 px-2">${statusBadge}</td>
                            <td class="py-3 px-2 capitalize text-xs">${type}</td>
                            <td class="py-3 px-2">${link}</td>
                            <td class="py-3 px-2 text-right text-xs text-gray-400">${dateStr}</td>
                        </tr>
                    `;
                });

                historyTableBody.innerHTML = html;
            }

            // --- CHAT WITH ASSISTANT ---
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const text = chatInput.value.trim();
                if (!text) return;

                // Add User Message
                addChatMessage('user', text);
                chatInput.value = '';

                // Add typing indicator
                const typingId = addChatTypingIndicator();

                // Send request
                fetch("{{ route('ai.insights') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ question: text })
                })
                .then(response => response.json())
                .then(data => {
                    removeChatTypingIndicator(typingId);
                    if (data.success) {
                        addChatMessage('ai', data.data.answer);
                    } else {
                        addChatMessage('ai', `Sorry, I encountered an error: ${data.message}`);
                    }
                })
                .catch(err => {
                    removeChatTypingIndicator(typingId);
                    addChatMessage('ai', `Sorry, something went wrong. Make sure you have configured your AI API key in your .env file.`);
                });
            });

            function addChatMessage(sender, text) {
                const bubbleClass = sender === 'user' ? 'chat-bubble-user' : 'chat-bubble-ai';
                const containerClass = sender === 'user' ? 'justify-end ml-auto' : 'items-start max-w-[85%]';
                const iconHtml = sender === 'user' 
                    ? '' 
                    : `<div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-950 flex items-center justify-center flex-shrink-0 text-purple-600"><span class="material-icons text-sm">smart_toy</span></div>`;

                // Convert markdown style bolding to HTML
                let formattedText = text
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>')
                    .replace(/\n/g, '<br>');

                const msgDiv = document.createElement('div');
                msgDiv.className = `flex gap-2.5 ${containerClass}`;
                msgDiv.innerHTML = `
                    ${iconHtml}
                    <div class="${bubbleClass} p-3 rounded-2xl text-sm leading-relaxed whitespace-pre-line">
                        ${formattedText}
                    </div>
                `;
                chatLog.appendChild(msgDiv);
                chatLog.scrollTop = chatLog.scrollHeight;
            }

            function addChatTypingIndicator() {
                const indicatorId = 'typing-' + Date.now();
                const msgDiv = document.createElement('div');
                msgDiv.className = `flex gap-2.5 items-start max-w-[85%]`;
                msgDiv.id = indicatorId;
                msgDiv.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-950 flex items-center justify-center flex-shrink-0 text-purple-600">
                        <span class="material-icons text-sm">smart_toy</span>
                    </div>
                    <div class="chat-bubble-ai p-4 rounded-2xl flex items-center justify-center">
                        <div class="typing-indicator">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                `;
                chatLog.appendChild(msgDiv);
                chatLog.scrollTop = chatLog.scrollHeight;
                return indicatorId;
            }

            function removeChatTypingIndicator(id) {
                const indicator = document.getElementById(id);
                if (indicator) indicator.remove();
            }
        });
    </script>
    @endpush
</x-layouts.admin>
