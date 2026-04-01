<?php

namespace App\Console\Commands;

use App\Models\Movie;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

class GetFilms extends Command
{
    protected $signature = 'app:get-films {pages=3}';

    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        parent::__construct();

        $this->baseUrl = config('services.movie_url');
        $this->token = config('services.movie_token');
    }

    public function handle()
    {
        $pages = (int) $this->argument('pages');

        for ($page = 1; $page <= $pages; $page++) {
            try {
                $response = Http::withToken($this->token)
                    ->get($this->baseUrl, ['page' => $page]);

                $data = $response->json();
                $movies = $data['docs'] ?? [];

                if (empty($movies)) {
                    break;
                }

                foreach ($movies as $movieData) {
                    $movie = Movie::firstOrCreate(
                        ['name' => $movieData['name']],
                        [
                            'budget_in_millions' => $movieData['budgetInMillions'] ?? null,
                        ]
                    );
                    \Log::debug($movie);
                }
            } catch (RequestException $e) {
                \Log::error('External Api error: ' . $e->getMessage(), [
                    'message' => $e->response?->body(),
                    'status' => $e->response?->status()
                ]);
                $this->error("Error requesting page {$page}: " . $e->getMessage());
                return 1; 
            }
        }
        return 0;
    }
}