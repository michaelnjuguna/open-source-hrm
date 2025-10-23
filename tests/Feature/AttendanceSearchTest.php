<?php


use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cannot search attendances by hours column', function () {
    // Create sample records
    Attendance::factory()->create([
        'clock_in' => '08:00',
        'clock_out' => '17:00',
    ]);

    // Try to search by 'hours' (which doesn't exist in DB)
    $response = $this->getJson('/attendances?search=hours:9');

    // Expect failure or ignore silently (depending on your logic)
    $response->assertStatus(400) // or 422, if you validate search fields
        ->assertJsonMissing(['data']); // no valid search results
});
