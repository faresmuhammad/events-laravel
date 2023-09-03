<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $events = Event::with(['user'])
            ->when(request('isLive'), function ($query) {
                $query->where('is_live', request('isLive'));
            })
            ->when(request('upcoming'), function ($query) {
                $query->where('is_live', false)
                    ->where('start_date', '>=', now());
            })
            ->when(\request('sortOrder'), function ($query) {
                $query->orderBy('start_date', \request('sortOrder'));
            })
            ->paginate();
        return EventResource::collection($events);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $event = Event::create([
                'title' => request('title'),
                'description' => request('description'),
                'start_date' => request('start_time'),
                'end_date' => request('end_time'),
                'location' => request('location'),
                'location_coordinates' => request('location_coordinates'),
                'user_id' => \Auth::id(),
                'profile_id' => request('profile_id'),
                'event_image' => request('event_image'),
            ]);
            return new EventResource($event);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Event Creation Failed',
                'error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::find($id);
        if ($event)
            return new EventResource($event);
        else
            return response()->json([
                'message' => 'Event Not Found'
            ], 404);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $event = Event::find($id);
            if ($event != null) {
                $this->authorize('update', $event);
                //todo: authorized user can update and who has the permission
                $event->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'start_date' => $request->start_time,
                    'end_date' => $request->end_time,
                    'location' => $request->location,
                    'location_coordinates' => $request->location_coordinates,
                    'event_image' => $request->event_image,
                ]);
                return new EventResource($event);
            } else {
                return response()->json([
                    'message' => "Event Not Found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Update event failed',
                'error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event != null) {
            $this->authorize('delete', $event);
            $event->delete();
            return response()->json([
                'message' => 'Event Deleted'
            ]);
        } else {
            return response()->json([
                'message' => 'Event is already deleted or it isn\'t exist'
            ], 404);
        }
    }


    public function toggleFollowing()
    {
        //Simulate the authorized user
        //todo: replace with the authorized user
        $user = auth()->user();
        $event = Event::find(\request('event_id'));
        $message = '';
        if ($event != null) {
            $followingAction = $user->following()->toggle($event);
            if (sizeof($followingAction['attached']) > 0)
                $message = 'You are now following ' . $event->title;
            if (sizeof($followingAction['detached']) > 0)
                $message = 'You unfollowed ' . $event->title;
        } else {
            $message = 'Event Not Found';
        }

        return response()->json([
            'message' => $message
        ]);
    }


}
