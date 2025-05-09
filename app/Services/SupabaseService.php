<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $client;
    protected $url;
    protected $key;
    protected $bucket;

    public function __construct()
    {
        $this->url = rtrim(env('SUPABASE_URL'), '/');
        $this->key = env('SUPABASE_SERVICE_ROLE_KEY'); // Pakai Service Role Key untuk izin penuh
        $this->bucket = env('SUPABASE_STORAGE_BUCKET');

        $this->client = new Client([
            'headers' => [
                'Authorization' => "Bearer {$this->key}",
                'apiKey' => $this->key,
            ]
        ]);
    }

    // Upload file ke Supabase Storage
    public function uploadFile(UploadedFile $file, $path)
    {
        try {
            $filePath = trim($path, '/'); // Hapus slash di awal dan akhir
            
            $response = $this->client->request('POST', "{$this->url}/storage/v1/object/{$this->bucket}/upload/{$filePath}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->key}",
                ],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file->path(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                return $filePath; // Kembalikan path file yang disimpan
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Supabase upload error: ' . $e->getMessage());
            return null;
        }
    }

    // Dapatkan URL file dari Supabase
    public function getFileUrl($filePath)
    {
        $filePath = trim($filePath, '/');
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$filePath}";
    }

    // Hapus file dari Supabase
    public function deleteFile($filePath)
    {
        try {
            $filePath = trim($filePath, '/');
            
            $response = $this->client->request('DELETE', "{$this->url}/storage/v1/object/{$this->bucket}/{$filePath}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->key}",
                    'apiKey' => $this->key,
                ]
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('Supabase delete error: ' . $e->getMessage());
            return false;
        }
    }
}
