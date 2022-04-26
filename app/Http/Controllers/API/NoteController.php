<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackEnd\Notes\Store;
use App\Http\Requests\BackEnd\Notes\Update;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Note::all();
        return response()->api(NoteResource::collection($notes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $image = Image::make($data['image'])
                ->resize(255, 378)
                ->encode('jpg');

            Storage::disk('local')->put('public/notes/' . $request->image->hashName(), (string)$image->getEncoded());
            $data['image'] = $request->image->hashName();

        }//end of if
        $data['user_id'] = 1;
        $note = Note::create($data);
        return response()->api(NoteResource::make($note));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $data = $request->validated();
        $note = Note::findOrfail($id);
        if ($request->image) {

            if ($note->image != null) {
                Storage::disk('local')->delete('public/notes/' . $note->image);
            }

            $image = Image::make($request->image)
                ->resize(255, 378)
                ->encode('jpg');

            Storage::disk('local')->put('public/notes/' . $request->image->hashName(), (string)$image->getEncoded());
            $data['image'] = $request->image->hashName();

        }//end of if
        $data['user_id'] = 1;
        $note->update($data);
        return response()->api(NoteResource::make($note));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::findOrfail($id);
        Storage::disk('local')->delete('public/notes/' . $note->image);
        $note->delete();
        return response()->api(NoteResource::make($note));
    }
}
