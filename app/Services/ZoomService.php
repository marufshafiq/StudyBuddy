<?php

namespace App\Services;

use App\Models\Meeting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ZoomService
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $accountId;
    protected $baseUrl = 'https://api.zoom.us/v2';

    public function __construct()
    {
        $this->client = new Client();
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->accountId = config('services.zoom.account_id');
    }

    /**
     * Get OAuth access token using Server-to-Server OAuth
     */
    protected function getAccessToken(): string
    {
        return Cache::remember('zoom_access_token', 3600, function () {
            $response = $this->client->post('https://zoom.us/oauth/token', [
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['access_token'];
        });
    }

    /**
     * Create a Zoom meeting for the given meeting model
     */
    public function createMeetingLink(Meeting $meeting): string
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->post("{$this->baseUrl}/users/me/meetings", [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'topic' => $meeting->title,
                    'type' => 2, // Scheduled meeting
                    'start_time' => $meeting->scheduled_at->toIso8601String(),
                    'duration' => $meeting->duration,
                    'timezone' => 'UTC',
                    'agenda' => $meeting->description ?? '',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => false,
                        'mute_upon_entry' => false,
                        'watermark' => false,
                        'audio' => 'both',
                        'auto_recording' => 'none',
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Update meeting with Zoom data
            $meeting->update([
                'meeting_link' => $data['join_url'],
                'google_meet_id' => $data['id'], // Store Zoom meeting ID
                'google_meet_data' => [
                    'zoom_meeting_id' => $data['id'],
                    'meeting_password' => $data['password'] ?? null,
                    'host_email' => $data['host_email'] ?? null,
                    'start_url' => $data['start_url'],
                    'join_url' => $data['join_url'],
                    'created_at' => now()->toIso8601String(),
                ],
            ]);

            return $data['join_url'];
        } catch (\Exception $e) {
            \Log::error('Zoom API Error: ' . $e->getMessage());
            throw new \Exception('Failed to create Zoom meeting: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing Zoom meeting
     */
    public function updateMeetingLink(Meeting $meeting): string
    {
        try {
            $zoomMeetingId = $meeting->google_meet_id;
            
            if (!$zoomMeetingId || $zoomMeetingId === 'pending') {
                return $this->createMeetingLink($meeting);
            }

            $accessToken = $this->getAccessToken();

            $response = $this->client->patch("{$this->baseUrl}/meetings/{$zoomMeetingId}", [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'topic' => $meeting->title,
                    'start_time' => $meeting->scheduled_at->toIso8601String(),
                    'duration' => $meeting->duration,
                    'agenda' => $meeting->description ?? '',
                ],
            ]);

            return $meeting->meeting_link;
        } catch (\Exception $e) {
            \Log::error('Zoom API Update Error: ' . $e->getMessage());
            // If update fails, create a new meeting
            return $this->createMeetingLink($meeting);
        }
    }

    /**
     * Delete a Zoom meeting
     */
    public function cancelMeeting(Meeting $meeting): bool
    {
        try {
            $zoomMeetingId = $meeting->google_meet_id;
            
            if (!$zoomMeetingId || $zoomMeetingId === 'pending') {
                $meeting->update(['status' => 'cancelled']);
                return true;
            }

            $accessToken = $this->getAccessToken();

            $this->client->delete("{$this->baseUrl}/meetings/{$zoomMeetingId}", [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                ],
            ]);

            $meeting->update(['status' => 'cancelled']);
            return true;
        } catch (\Exception $e) {
            \Log::error('Zoom API Delete Error: ' . $e->getMessage());
            $meeting->update(['status' => 'cancelled']);
            return true;
        }
    }

    /**
     * Get meeting details from Zoom
     */
    public function getMeetingDetails(string $zoomMeetingId): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->get("{$this->baseUrl}/meetings/{$zoomMeetingId}", [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Log::error('Zoom API Get Details Error: ' . $e->getMessage());
            throw new \Exception('Failed to get Zoom meeting details');
        }
    }
}
