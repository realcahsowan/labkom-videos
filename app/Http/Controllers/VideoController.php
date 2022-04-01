<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $user->load('videos');

        return view('videos.index', ['videos' => $user->videos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('videos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate($this->getValidationRules());

        $hashname = Str::random() . '.mp4';

        try {
            $data['video_file']->storeAs('videos', $hashname);

            $video = new Video();
            $video->title = $data['title'];
            $video->description = $data['description'];
            $video->size = $data['video_file']->getSize();
            $video->filename = $data['video_file']->getClientOriginalName();
            $video->hashname = $hashname;
            $video->user()->associate($user);
            $video->save();

            $message = 'New video stored.';
            $url = route('videos.index');
            return redirect($url)->with('status', $message);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('status', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $this->authorize('delete', $video);

        $path = storage_path('app/videos/' . $video->hashname);
        try {
            File::delete($path);
            $video->delete();
            $message = 'New video deleted.';
            $url = route('videos.index');
            return redirect($url)->with('status', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('status', $e->getMessage());
        }
    }

    public function getValidationRules()
    {
        return [
            'video_file' => 'required|max:100000|mimes:mp4',
            'description' => 'present|max:500',
            'title' => 'required|min:10',
        ];
    }
}
