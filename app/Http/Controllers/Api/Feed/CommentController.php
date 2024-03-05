<?php

namespace App\Http\Controllers\Api\Feed;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Feed\CommentRequest;

class CommentController extends ApiController
{
    public function __invoke(CommentRequest $request, Feed $feed)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user->comments()->attach($feed->id, ['body' => $request->body]);
            DB::commit();
            return $this->successResponse(null, 'Comment created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
