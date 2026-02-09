<?php
// app/Services/SecureDocumentService.php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SecureDocumentService
{
    protected $disk;
    protected $encryptionEnabled;

    public function __construct()
    {
        $this->disk = config('documents.storage.disk', 'local');
        $this->encryptionEnabled = config('documents.encryption.enabled', true);
    }

    /**
     * Securely upload document with encryption
     */
    public function secureUpload($file, $type = 'document')
    {
        try {
            // Generate secure filename
            $extension = $file->getClientOriginalExtension();
            $filename = Str::random(40) . '_' . time() . '.' . $extension;

            // Read file content
            $content = file_get_contents($file->getRealPath());

            // Encrypt if enabled
            if ($this->encryptionEnabled) {
                $content = encrypt($content);
            }

            // Save to secure storage
            $directory = config('documents.storage.path') . '/' . $type;
            $path = $directory . '/' . $filename;

            Storage::disk($this->disk)->put($path, $content);

            Log::info('Document securely uploaded', [
                'type' => $type,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'encrypted' => $this->encryptionEnabled,
                'size_kb' => round($file->getSize() / 1024, 2)
            ]);

            return $path;

        } catch (\Exception $e) {
            Log::error('Secure Upload Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Securely read and decrypt document
     */
    public function secureRead($path)
    {
        try {
            if (!Storage::disk($this->disk)->exists($path)) {
                throw new \Exception('Document not found: ' . $path);
            }

            // Read encrypted content
            $content = Storage::disk($this->disk)->get($path);

            // Decrypt if encryption enabled
            if ($this->encryptionEnabled) {
                $content = decrypt($content);
            }

            return $content;

        } catch (\Exception $e) {
            Log::error('Secure Read Error: ' . $e->getMessage(), [
                'path' => $path
            ]);
            throw $e;
        }
    }

    /**
     * Securely delete document
     */
    public function secureDelete($path)
    {
        try {
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);

                Log::info('Document securely deleted', ['path' => $path]);

                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Secure Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if document exists
     */
    public function exists($path)
    {
        return Storage::disk($this->disk)->exists($path);
    }
}
