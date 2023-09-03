<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileCollection;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except('index', 'show', 'test');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProfileCollection(Profile::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Profile::class);
        try {
            $profile = Profile::create([
                'name' => \request('name'),
                'type' => request('type'),
                'social_media_profiles' => \request('socialLinks')
            ]);
            $profile->organizers()->sync(auth()->id(), false);
            return new ProfileResource($profile);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Profile Creation Failed',
                'error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        return new ProfileResource($profile);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        $this->authorize('update', $profile);
        $profile->update([
            'name' => $request->name
        ]);
        return response()->json([
            'profile' => $profile
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        $this->authorize('delete', $profile);
        $profile->delete();
        return response()->json([
            'message' => 'Profile Deleted'
        ]);
    }

    public function test()
    {
        $test = collect([1, 2, 3, 4, 5, 6]);
        return response()->json([
            'message' => $test->contains(fn($item) => in_array($item, [1, -4, 0]))
        ]);
    }
}
