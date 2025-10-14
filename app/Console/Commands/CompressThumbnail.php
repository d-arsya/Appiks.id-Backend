<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CompressThumbnail extends Command
{
    protected $description = 'Compress image thumbnails';

    protected $signature = 'compress:thumbnail';

    public function handle()
    {
        try {
            $disk = Storage::disk('public');
            $ap = env('APP_URL');
            $article = Article::where('thumbnail', 'like', "$ap%")
                ->where('thumbnail', 'not like', '%_com.webp')
                ->first();
            if ($article == null) {
                return 0;
            }
            $filepath = str_replace("$ap/storage/", '', $article->thumbnail);
            if ($disk->exists($filepath)) {
                $response = Http::withHeaders([
                    'x-api-key' => config('app.image_compress_key'),
                ])->attach(
                    'image',
                    fopen($disk->path($filepath), 'r'),
                    $filepath
                )->post(config('app.image_compress_url'));
                $newFile = preg_replace('/\.\w+$/', '_com.webp', $filepath);
                if ($disk->exists($newFile)) {
                    $disk->delete($newFile);
                }
                $disk->put($newFile, $response->body());
                $article->thumbnail = $ap.'/storage/'.$newFile;
                $article->save();
                $disk->delete($filepath);
            }
            $this->info('Sukses compress gambar');
        } catch (\Throwable $th) {
            $this->info('Gagal compress gambar'.$th->getMessage());
        }

        return 0;
    }
}
