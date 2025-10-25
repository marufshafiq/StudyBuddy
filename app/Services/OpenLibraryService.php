<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class OpenLibraryService
{
    protected $client;
    protected $baseUrl = 'https://openlibrary.org';

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'verify' => false, // For local development
        ]);
    }

    /**
     * Search for books by query (title, author, subject)
     * 
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function searchBooks(string $query, int $limit = 20): array
    {
        try {
            $cacheKey = 'openlibrary_search_' . md5($query . $limit);
            
            return Cache::remember($cacheKey, 3600, function () use ($query, $limit) {
                $response = $this->client->get("{$this->baseUrl}/search.json", [
                    'query' => [
                        'q' => $query,
                        'limit' => $limit,
                        'fields' => 'key,title,author_name,first_publish_year,cover_i,isbn,subject,publisher,language',
                    ],
                ]);

                $data = json_decode($response->getBody(), true);
                
                return $this->formatSearchResults($data['docs'] ?? []);
            });
        } catch (\Exception $e) {
            \Log::error('OpenLibrary API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Search books by subject/category
     * 
     * @param string $subject
     * @param int $limit
     * @return array
     */
    public function searchBySubject(string $subject, int $limit = 20): array
    {
        try {
            $cacheKey = 'openlibrary_subject_' . md5($subject . $limit);
            
            return Cache::remember($cacheKey, 3600, function () use ($subject, $limit) {
                $response = $this->client->get("{$this->baseUrl}/subjects/{$subject}.json", [
                    'query' => ['limit' => $limit],
                ]);

                $data = json_decode($response->getBody(), true);
                
                return $this->formatSubjectResults($data['works'] ?? []);
            });
        } catch (\Exception $e) {
            \Log::error('OpenLibrary Subject API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get book details by OpenLibrary key
     * 
     * @param string $key (e.g., /works/OL45883W)
     * @return array|null
     */
    public function getBookDetails(string $key): ?array
    {
        try {
            $cacheKey = 'openlibrary_book_' . md5($key);
            
            return Cache::remember($cacheKey, 86400, function () use ($key) {
                $response = $this->client->get("{$this->baseUrl}{$key}.json");
                $data = json_decode($response->getBody(), true);
                
                return $this->formatBookDetails($data);
            });
        } catch (\Exception $e) {
            \Log::error('OpenLibrary Book Details Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get cover image URL
     * 
     * @param int|null $coverId
     * @param string $size (S, M, L)
     * @return string|null
     */
    public function getCoverUrl(?int $coverId, string $size = 'M'): ?string
    {
        if (!$coverId) {
            return null;
        }
        
        return "https://covers.openlibrary.org/b/id/{$coverId}-{$size}.jpg";
    }

    /**
     * Format search results for display
     * 
     * @param array $docs
     * @return array
     */
    protected function formatSearchResults(array $docs): array
    {
        return array_map(function ($doc) {
            $key = $doc['key'] ?? null;
            return [
                'key' => $key,
                'title' => $doc['title'] ?? 'Unknown Title',
                'authors' => $doc['author_name'] ?? [],
                'first_publish_year' => $doc['first_publish_year'] ?? null,
                'cover_id' => $doc['cover_i'] ?? null,
                'cover_url' => $this->getCoverUrl($doc['cover_i'] ?? null),
                'isbn' => isset($doc['isbn']) ? $doc['isbn'][0] ?? null : null,
                'subjects' => array_slice($doc['subject'] ?? [], 0, 5),
                'publishers' => isset($doc['publisher']) ? array_slice($doc['publisher'], 0, 3) : [],
                'languages' => $doc['language'] ?? [],
                'read_url' => $key ? "https://openlibrary.org{$key}" : null,
                'has_fulltext' => $doc['has_fulltext'] ?? false,
            ];
        }, $docs);
    }

    /**
     * Format subject search results
     * 
     * @param array $works
     * @return array
     */
    protected function formatSubjectResults(array $works): array
    {
        return array_map(function ($work) {
            $key = $work['key'] ?? null;
            return [
                'key' => $key,
                'title' => $work['title'] ?? 'Unknown Title',
                'authors' => array_map(fn($a) => $a['name'] ?? '', $work['authors'] ?? []),
                'first_publish_year' => $work['first_publish_year'] ?? null,
                'cover_id' => $work['cover_id'] ?? null,
                'cover_url' => $this->getCoverUrl($work['cover_id'] ?? null),
                'subjects' => array_slice($work['subject'] ?? [], 0, 5),
                'read_url' => $key ? "https://openlibrary.org{$key}" : null,
                'has_fulltext' => $work['has_fulltext'] ?? false,
            ];
        }, $works);
    }

    /**
     * Format book details
     * 
     * @param array $data
     * @return array
     */
    protected function formatBookDetails(array $data): array
    {
        $description = '';
        if (isset($data['description'])) {
            $description = is_array($data['description']) 
                ? ($data['description']['value'] ?? '') 
                : $data['description'];
        }

        return [
            'key' => $data['key'] ?? null,
            'title' => $data['title'] ?? 'Unknown Title',
            'description' => $description,
            'subjects' => $data['subjects'] ?? [],
            'created' => $data['created']['value'] ?? null,
            'covers' => $data['covers'] ?? [],
            'cover_url' => isset($data['covers'][0]) 
                ? $this->getCoverUrl($data['covers'][0]) 
                : null,
        ];
    }

    /**
     * Get popular subjects for browsing
     * 
     * @return array
     */
    public function getPopularSubjects(): array
    {
        return [
            'mathematics' => '📐 Mathematics',
            'science' => '🔬 Science',
            'history' => '📜 History',
            'literature' => '📚 Literature',
            'programming' => '💻 Programming',
            'physics' => '⚛️ Physics',
            'chemistry' => '🧪 Chemistry',
            'biology' => '🧬 Biology',
            'psychology' => '🧠 Psychology',
            'philosophy' => '💭 Philosophy',
            'art' => '🎨 Art',
            'music' => '🎵 Music',
            'economics' => '💰 Economics',
            'engineering' => '⚙️ Engineering',
            'computer_science' => '🖥️ Computer Science',
        ];
    }
}
