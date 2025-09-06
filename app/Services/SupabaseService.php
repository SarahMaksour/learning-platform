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

  public function uploadImage($file)
  {
    Log::info("uploading file to supabase: {$file}");
    $filepath = $file->getClientOriginalName();

    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . $this->apiKey,
    ])
      ->attach('file', $file->get(), $file->getClientOriginalName())
      ->post(
        "{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$filepath}"
      );

    if ($response->successful()) {
      return $response->json();
    } else {
      // Handle the case where the upload was not successful
      throw new \Exception('Failed to upload image to Supabase: ' . $response->body());
    }
  }

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
}