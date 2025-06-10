<?php

namespace App\Services;

use Google_Client;

class CalendarService
{
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->setAuthConfig();
        $this->client->setScopes(\Google_Service_Calendar::CALENDAR);
        $this->configHttpClient();
    }

    private function setAuthConfig()
    {
        $accessTokenPath = storage_path('app/google-calendar/W-A_api.json');
        if (file_exists($accessTokenPath)) {
            $accessToken = json_decode(file_get_contents($accessTokenPath), true);
            $this->client->setAuthConfig($accessToken);
            // $this->client->setAccessToken($accessToken);
        } else {
            throw new \Exception('Access token not found. Please authenticate first.');
        }
    }

    private function configHttpClient()
    {
        $httpClient = $this->client->authorize();
        return $httpClient;
    }

    public function addEvent($data)
    {
        $service = new \Google_Service_Calendar($this->client);
        $calendarId = config('services.googlecalendar.calendar_id');
        $eventData = [
            'summary' => "{$data['asunto']} 🚗",
            'description' => $data['descripcion'],
            'start' => [
                'dateTime' => $data['inicio'],
                'timeZone' => 'Europe/Madrid',
            ],
            'end' => [
                'dateTime' => $data['fin'],
                'timeZone' => 'Europe/Madrid',
            ],
            'reminders' => [
                'useDefault' => FALSE,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 24 * 60],
                    ['method' => 'popup', 'minutes' => 10]
                ]
            ],
            'colorId' => 1,
        ];
        $event = new \Google_Service_Calendar_Event($eventData);

        try {
            $event = $service->events->insert($calendarId, $event);
            return $event;
        } catch (\Exception $e) {
            throw new \Exception('Error adding event: ' . $e->getMessage());
        }
    }

    public function updateEvent($eventId, $data)
    {
        $service = new \Google_Service_Calendar($this->client);
        $calendarId = config('services.googlecalendar.calendar_id');

        try {
            $event = $service->events->get($calendarId, $eventId);
            $event->setSummary("{$data['asunto']} 🚗");
            $event->setDescription($data['descripcion']);
            $event->setStart(new \Google_Service_Calendar_EventDateTime([
                'dateTime' => $data['inicio'],
                'timeZone' => 'Europe/Madrid',
            ]));
            $event->setEnd(new \Google_Service_Calendar_EventDateTime([
                'dateTime' => $data['fin'],
                'timeZone' => 'Europe/Madrid',
            ]));

            return $service->events->update($calendarId, $eventId, $event);
        } catch (\Exception $e) {
            throw new \Exception('Error updating event: ' . $e->getMessage());
        }
    }
}
