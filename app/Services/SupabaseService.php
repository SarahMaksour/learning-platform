<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

const SECONDS_IN_DAY = 86400;

class SupabaseService
{
  private $supabaseUrl;
  private $apiKey;
  private $bucketName;

  public function __construct()
  {
    $this->supabaseUrl = env('SUPABASE_URL');
    $this->apiKey = env('SUPABASE_KEY');
    $this->bucketName = env('SUPABASE_BUCKET');
  }

 public function uploadImage($file, $customName = null)
{
    Log::info("uploading file to supabase: {$file}");
    
    // إذا تم تمرير اسم مخصص استخدمه، وإلا استخدم الاسم الأصلي
    $filepath = $customName ?? $file->getClientOriginalName();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
    ])
    ->attach('file', $file->get(), $filepath)
    ->post("{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$filepath}");

    if ($response->successful()) {
        return $response->json();
    } else {
        throw new \Exception('Failed to upload image to Supabase: ' . $response->body());
    }
}

/*
public function uploadImage($file, $customName = null, $upsert = true) // default true
{
    Log::info("uploading file to supabase: {$file}");
    
    $filepath = $customName ?? $file->getClientOriginalName();
    $url = "{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$filepath}?upsert={$upsert}";

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
    ])
    ->attach('file', $file->get(), $filepath)
    ->post($url);

    if ($response->successful()) {
        return $response->json();
    } else {
        throw new \Exception('Failed to upload image to Supabase: ' . $response->body());
    }*/





  public function getSignedUrl($file)
  {
    $filepath = $file->getClientOriginalName();
    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . $this->apiKey,
    ])
      ->post("{$this->supabaseUrl}/storage/v1/object/sign/{$this->bucketName}/{$filepath}", [
        "expiresIn" => 999 * SECONDS_IN_DAY,
        // "transform" => [
        //   "height" => 100,
        //   "width" => 100,
        //   "resize" => "cover",
        //   "format" => "origin",
        //   "quality" => 100
        // ]
      ]);


    if ($response->successful()) {
      return $this->supabaseUrl . "/storage/v1" . $response->json()['signedURL'];
    } else {
      // Handle the case where the request was not successful
      throw new \Exception('Failed to retrieve signed URL from Supabase: ' . $response->body());
    }
  }
  public function deleteImage(string $fileName)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
    ])->delete("{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$fileName}");

    if (!$response->successful()) {
        throw new \Exception('Failed to delete image from Supabase: ' . $response->body());
    }
}

}