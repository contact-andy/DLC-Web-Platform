<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use App\Models\DigitalContent;

use Illuminate\Http\Request;

class WatchController extends Controller
{
    
    public function getWatchedContent($userId, $videoId)
    {
        // SELECT * FROM `watched` WHERE  userId = ? and videoId=?
        $content = Watch::where('userId',$userId)->where('videoId',$videoId)->where('watch',1)->get();
        return response()->json(['content' => $content], 201);
    }

    public function getLikedContent($userId, $videoId, $type)
    {
        if($type==0){
            $content = Watch::where('userId',$userId)->where('videoId',$videoId)->where('like',1)->get();
            return response()->json(['content' => $content], 201);
        }
        else{
            $contentCount = Watch::where('videoId',$videoId)->where('like',1)->count();
            return response()->json(['content' => $contentCount], 201);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function likeContent(Request $request)
    {
        $type = $request->type;
        $userId = $request->userId;
        $videoId = $request->videoId;
        $contentLikeCount = Watch::where('userId',$userId)->where('videoId',$videoId)->count();
        if($contentLikeCount){
            //Update
            $contentLike = Watch::where('userId',$userId)->where('videoId',$videoId)->first();
            $likeContent = Watch::find($contentLike->id);
            $likeContent->like = $type;
            $likeContent->save();
        }
        else{
            $likeContent = Watch::create([
                'userId' => $userId,
                'videoId' => $videoId,
                'like' =>  $type,
            ]);
        }
        Watch::where('userId',$userId)->where('videoId',$videoId)->where('watch',0)->where('like',0)->delete();
        $content = DigitalContent::where('id',$videoId)->get();
        return response()->json(['content' => $content], 201);
        // return response()->json(['likeContent' => $likeContent], 201);
    }

    public function watchContent(Request $request)
    {
        $type = $request->type;
        $userId = $request->userId;
        $videoId = $request->videoId;
        $contentWatchCount = Watch::where('userId',$userId)->where('videoId',$videoId)->count();
        if($contentWatchCount){
            //Update
            $contentWatch = Watch::where('userId',$userId)->where('videoId',$videoId)->first();
            $watchContent = Watch::find($contentWatch->id);
            $watchContent->watch = $type;
            $watchContent->save();
        }
        else{
            $watchContent = Watch::create([
                'userId' => $userId,
                'videoId' => $videoId,
                'watch' =>  $type,
            ]);
        }
        Watch::where('userId',$userId)->where('videoId',$videoId)->where('watch',0)->where('like',0)->delete();
        $content = DigitalContent::where('id',$videoId)->get();
        return response()->json(['content' => $content], 201);
        // return response()->json(['watchContent' => $watchContent], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Watch $watch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Watch $watch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Watch $watch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Watch $watch)
    {
        //
    }
}
