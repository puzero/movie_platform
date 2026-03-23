<?php

namespace App\Console\Commands;

use App\Models\Movie;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

class getFilms extends Command
{
    protected $signature = 'app:get-films {pages=3}';

    protected string $baseUrl = "https://the-one-api.dev/v2/movie";
    protected string $token = "ZP9zGomhPT1Nrl0p6hzo";

    public function __construct()
    {
        parent::__construct();
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
                    Movie::firstOrCreate(
                        ['name' => $movieData['name']],
                        [
                            'budget_in_millions' => $movieData['budgetInMillions'] ?? null,
                        ]
                    );
                }
            } catch (RequestException $e) {
                \Log::error('External Api error: ' . $e->getMessage(), [
                    'message' => $e->response?->body(),
                    'status' => $e->response?->status()
                ]);
                $this->error("Ошибка при запросе страницы {$page}: " . $e->getMessage());
                return 1; 
            }
        }
        return 0;
    }
}