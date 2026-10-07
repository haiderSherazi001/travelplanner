<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // Imports the upload trait
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripChat extends Component
{
    use WithFileUploads; // Activates upload capabilities

    public Trip $trip;
    public $content = '';
    public $file; // Holds our uploaded file

    public function mount(Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized');
        }
        $this->trip = $trip;
    }

    public function sendMessage()
    {
        // Added audio formats to the mimes list (webm is the browser standard for recorded audio)
        $this->validate([
            'content' => 'required_without:file|string|max:1000|nullable',
            'file' => 'nullable|file|max:10240|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,txt,webm,wav,mp3,m4a,ogg', 
        ]);

        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($this->file) {
            $filePath = $this->file->store('chat-media', 'public');
            $fileName = $this->file->getClientOriginalName();
            
            $mimeType = $this->file->getMimeType();
            
            // Detect file type
            if (str_starts_with($mimeType, 'image/')) {
                $fileType = 'image';
            } elseif (str_starts_with($mimeType, 'audio/') || str_ends_with($fileName, '.webm')) {
                $fileType = 'audio'; // Catch Voice Notes
            } else {
                $fileType = 'document';
            }
        }

        $message = $this->trip->messages()->create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        $this->reset(['content', 'file']);
    }

    public function render()
    {
        return view('livewire.trip-chat', [
            'messages' => $this->trip->messages()->with('user')->oldest()->get()
        ]);
    }
}