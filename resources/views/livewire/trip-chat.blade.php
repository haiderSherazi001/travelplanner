<div class="flex flex-col h-full bg-white border-l border-gray-200">
    <!-- Chat Header -->
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
        <h3 class="font-bold text-gray-800 text-sm">Group Chat</h3>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 p-3 overflow-y-auto flex flex-col gap-3" 
         x-data
         x-init="
             Echo.private('trip.{{ $trip->id }}')
                 .listen('MessageSent', (e) => {
                     $wire.$refresh();
                 });
         ">
        @forelse ($messages as $message)
            @php $isMine = $message->user_id === auth()->id(); @endphp
            
            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">
                <span class="text-[10px] text-gray-500 mb-0.5 font-medium px-1">
                    {{ $isMine ? 'You' : $message->user->name }} • {{ $message->created_at->format('g:i A') }}
                </span>
                
                <div class="{{ $isMine ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800' }} px-3 py-2 rounded-xl max-w-[85%] shadow-sm">
                    
                    <!-- File Rendering -->
                    @if($message->file_path)
                        @if($message->file_type === 'image')
                            <div class="mb-1">
                                <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $message->file_path) }}" class="rounded-lg max-w-[200px] max-h-[200px] object-cover border {{ $isMine ? 'border-indigo-500' : 'border-gray-200' }} shadow-sm hover:opacity-90 transition">
                                </a>
                            </div>
                        @elseif($message->file_type === 'audio')
                            <div class="mb-1">
                                <!-- Audio Player -->
                                <audio controls class="max-w-[220px] h-10 rounded-lg">
                                    <source src="{{ asset('storage/' . $message->file_path) }}" type="audio/webm">
                                    Your browser does not support audio.
                                </audio>
                            </div>
                        @else
                            <div class="mb-1 flex items-center gap-2 {{ $isMine ? 'bg-indigo-700' : 'bg-white' }} p-2 rounded border {{ $isMine ? 'border-indigo-500' : 'border-gray-200' }}">
                                <svg class="w-5 h-5 {{ $isMine ? 'text-indigo-200' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <a href="{{ asset('storage/' . $message->file_path) }}" download class="text-sm font-bold truncate max-w-[150px] {{ $isMine ? 'text-white hover:text-indigo-100' : 'text-indigo-600 hover:underline' }}">
                                    {{ $message->file_name }}
                                </a>
                            </div>
                        @endif
                    @endif

                    <!-- Text Content -->
                    @if($message->content)
                        <p class="text-sm leading-relaxed">{{ $message->content }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="flex-1 flex items-center justify-center">
                <p class="text-sm text-gray-400 text-center px-4">Say hello to start the conversation!</p>
            </div>
        @endforelse
    </div>

    <!-- Input Area with Voice Note Logic -->
    <form wire:submit="sendMessage" class="p-3 bg-gray-50 border-t border-gray-200">
        
        <!-- File / Voice Note Preview Bar -->
        @if($file)
            <div class="mb-2 p-2 bg-white rounded-md border border-gray-200 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2 overflow-hidden">
                    <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    <span class="text-xs font-bold text-gray-700 truncate">
                        {{ $file->getClientOriginalName() }} Ready to send!
                    </span>
                </div>
                <button type="button" wire:click="$set('file', null)" class="text-gray-400 hover:text-red-500 ml-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <div class="flex gap-2 items-end relative"
             x-data="{
                isRecording: false,
                mediaRecorder: null,
                chunks: [],
                toggleRecording() {
                    if (this.isRecording) {
                        this.mediaRecorder.stop();
                        this.isRecording = false;
                    } else {
                        navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
                            this.mediaRecorder = new MediaRecorder(stream);
                            this.chunks = [];
                            this.mediaRecorder.ondataavailable = e => this.chunks.push(e.data);
                            this.mediaRecorder.onstop = () => {
                                // Create the audio file
                                let blob = new Blob(this.chunks, { type: 'audio/webm' });
                                let file = new File([blob], 'voice-note.webm', { type: 'audio/webm' });
                                
                                // Magically upload it to Livewire's $file property
                                @this.upload('file', file);
                                
                                // Turn off the mic hardware
                                stream.getTracks().forEach(track => track.stop());
                            };
                            this.mediaRecorder.start();
                            this.isRecording = true;
                        }).catch(err => alert('Microphone access denied or unavailable.'));
                    }
                }
             }"
        >
            <!-- Paperclip Upload Button -->
            <input type="file" wire:model="file" id="chatFile" class="hidden" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,audio/*">
            <label for="chatFile" class="cursor-pointer p-2 mb-0.5 text-gray-500 hover:text-indigo-600 transition bg-white rounded-md border border-gray-300 shadow-sm flex-shrink-0" title="Attach File">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
            </label>

            <!-- Voice Note Mic Button -->
            <button type="button" @click="toggleRecording()" 
                    :class="isRecording ? 'text-red-600 bg-red-50 border-red-300 animate-pulse' : 'text-gray-500 hover:text-indigo-600 bg-white border-gray-300'" 
                    class="p-2 mb-0.5 transition rounded-md border shadow-sm flex-shrink-0" title="Voice Note">
                
                <!-- Mic Icon (Default) -->
                <svg x-show="!isRecording" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                
                <!-- Stop Icon (When Recording) -->
                <svg x-show="isRecording" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h12v12H6z"></path></svg>
            </button>

            <!-- Text Input -->
            <div class="flex-1 relative">
                <!-- Loading indicator while file/voice note is uploading -->
                <div wire:loading wire:target="file" class="absolute top-0 right-0 p-2">
                    <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
                
                <input type="text" wire:model="content" :placeholder="isRecording ? 'Recording audio...' : 'Type a message...'" :disabled="isRecording" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 pl-3 pr-10" wire:loading.attr="disabled" wire:target="file">
            </div>

            <!-- Send Button -->
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition font-bold text-sm shadow-sm mb-0.5" wire:loading.attr="disabled" :disabled="isRecording">
                Send
            </button>
        </div>
    </form>
</div>