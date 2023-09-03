<?php

namespace Tests\Feature;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Trait\UserRoles;

class EventsTest extends TestCase
{
    use RefreshDatabase, UserRoles;

    /**
     * The reference is event policy
     */
    //todo: show all events
    //todo: show a single event
    //todo: create an event [required: authentication]
    //todo: update an event [required: authentication, authorization (roles)
    //todo: delete an event [required: authentication, authorization (roles)
    //todo: follow & unfollow an event


    protected function setUp(): void
    {
        parent::setUp();
        Event::unsetEventDispatcher();
        $this->withoutExceptionHandling();
        $this->seed(RolesSeeder::class);

    }

    public function test_authorized_user_and_guest_can_see_all_events()
    {

        $user = $this->user();
        Event::factory()->count(5)->create();
        $guestResponse = $this->getJson('/api/events');
        $authenticatedResponse = $this->actingAs($user)->getJson('/api/events');

        $authenticatedResponse->assertStatus(200)->assertJsonStructure([
            'data'
        ])->assertJsonCount(5, 'data');

        $guestResponse->assertStatus(200)->assertJsonStructure([
            'data'
        ])->assertJsonCount(5, 'data');
    }


    public function test_authorized_user_and_guest_can_see_a_single_event()
    {

        $user = $this->user();
        $event = Event::factory()->create();
        $eventJsonStructure = [
            'data' => [
                "title",
                "description",
                "startDate",
                "endDate",
                "location",
                "locationCoordinates" => [
                    "latitude",
                    "longitude",
                ],
                "isLive",
                "eventImage",
                "user"
            ]
        ];
        $guestResponse = $this->getJson('/api/events/' . $event->id);
        $authenticatedResponse = $this->actingAs($user)->getJson('/api/events/' . $event->id);
        $authenticatedResponse->assertStatus(200)->assertJsonStructure($eventJsonStructure);
        $guestResponse->assertStatus(200)->assertJsonStructure($eventJsonStructure);
    }

    public function test_an_authorized_user_can_create_an_event()
    {
        $user = $this->adminUser();


        $eventData = [
            'title' => 'Sample Event',
            'description' => 'This is a test event',
            'start_date' => now(),
            'end_date' => Carbon::tomorrow(),
            'location' => 'Alexandria,sdsd',
            'location_coordinates' => [
                'latitude' => 32.9878378,
                'longitude' => -113.876467
            ],
            'profile_id' => $user->profiles()->first()->id,
            'event_image' => null
        ];

        $response = $this->actingAs($user)->postJson('/api/events', $eventData);

        $response->assertStatus(201);

        $event = Event::first();;
        $response->assertJsonPath('data.id', $event->id);

    }


    public function test_system_admin_and_authorized_user_can_update_event()
    {
        $systemAdmin = $this->adminUser();

        $regularUser = $this->regularUser();

//        dd($systemAdmin->roles()->first()->name,$regularUser->roles()->count());
        $event = Event::factory()->create([
            'user_id' => $systemAdmin->id,
            'profile_id' => $systemAdmin->profiles()->first()->id
        ]);

        $updatedData = [
            'title' => 'Updated Event Title',
            'description' => $event->description,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'location' => $event->location,
            'location_coordinates' => $event->location_coordinates,
            'event_image' => $event->event_image,
        ];

//        $guest = $this->put('/api/events/' . $event->id, $updatedData);
//        $guest->assertStatus(401);
        $adminResponse = $this->actingAs($systemAdmin)->putJson('/api/events/' . $event->id, $updatedData);
        $userResponse = $this->actingAs($regularUser)->putJson('/api/events/' . $event->id, $updatedData);

//        dd($adminResponse);
        $adminResponse->assertJson([
            'data' => [
                "title" => "Updated Event Title"
            ]
        ]);
        $userResponse->assertOk()->assertJsonPath('data.title', 'Updated Event Title');

    }

    public function test_authenticated_user_and_system_admin_can_delete_an_event()
    {
        $systemAdmin = User::factory()
            ->hasAttached(Profile::factory())
            ->create();
        $systemAdmin->roles()->sync([Role::SYSTEM_ADMIN], false);

        $event = Event::factory()->create();

        dd($event->deleted_at);
        $response = $this->actingAs($systemAdmin)->deleteJson('/api/events/' . $event->id);

        dd($response);
//        $response->assertStatus(204);
        $this->assertNotNull($event->deleted_at);
    }
}


