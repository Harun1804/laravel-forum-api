<?php

namespace App\Http\Controllers\Api\Feed;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Feed\StoreFeedRequest;
use App\Http\Requests\Api\Feed\UpdateFeedRequest;
use Illuminate\Support\Facades\Auth;

class FeedController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $feeds = Feed::with('user')->get();
            return $this->successResponse($feeds, 'Feeds retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(StoreFeedRequest $request)
    {
        DB::beginTransaction();
        try {
            Auth::user()->feeds()->create($request->validated());
            DB::commit();
            return $this->successResponse(null, 'Feed created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show(Feed $feed)
    {
        try {
            $feed->load('user');
            return $this->successResponse($feed, 'Feed retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UpdateFeedRequest $request, Feed $feed)
    {
        DB::beginTransaction();
        try {
            $feed->update($request->validated());
            DB::commit();
            return $this->successResponse(null, 'Feed updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(Feed $feed)
    {
        DB::beginTransaction();
        try {
            $feed->delete();
            DB::commit();
            return $this->successResponse(null, 'Feed deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function likeFeed(Feed $feed)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $status = "";
            if($user->likes->contains($feed->id)) {
                $user->likes()->detach($feed->id);
                $status = "unliked";
            } else {
                $user->likes()->attach($feed->id);
                $status = "liked";
            }
            DB::commit();
            return $this->successResponse(null, "Feed has been {$status} successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
