<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $page = 1;
        $repositoryFullName = 'laravel/laravel';
        $state = 'all open';

        dd(rawurlencode($repositoryFullName));


        $query = http_build_query(
            [
                'state' => $state,
                'page' => $page,
            ],
            arg_separator: '&', encoding_type: PHP_QUERY_RFC3986
        );

        dd($query);

        $url = 'repos/' . $repositoryFullName . '/pulls?' . http_build_query(
            [
                'state' => $state,
                'page' => $page,
            ]
        );

        dump($url);
        // $response = $this->get('/');

        // $response->assertStatus(200);
    }
}
