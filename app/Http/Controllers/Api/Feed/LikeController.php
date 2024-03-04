<?php

namespace App\Http\Controllers\Api\Feed;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;

class LikeController extends ApiController
{
    public function __invoke(Feed $feed)
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
