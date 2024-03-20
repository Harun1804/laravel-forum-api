<?php

namespace App\Http\Controllers\Api\Feed;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Feed\StoreFeedRequest;
use App\Http\Requests\Api\Feed\UpdateFeedRequest;

class FeedController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $feeds = Feed::with('user', 'comments')->withCount(['likes', 'comments'])->latest()->get();
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
            $feed->load(['user:id,name', 'comments:id,name']);
            $feed->loadCount('likes', 'comments');
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
}
