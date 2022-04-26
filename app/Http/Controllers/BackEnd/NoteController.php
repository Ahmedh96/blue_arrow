<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackEnd\Notes\Store;
use App\Http\Requests\BackEnd\Notes\Update;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Note::with('user')->latest()->paginate(10);
        return view('backEnd.notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.notes.create');
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

        $data['user_id'] = auth()->user()->id;

        $note = Note::create($data);
        return redirect()->route('notes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Note::with('user')->findOrfail($id);
        return view('backEnd.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note = Note::findOrfail($id);
        return view('backEnd.notes.edit', compact('note'));
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
        $data['user_id'] = auth()->user()->id;
        $note->update($data);
        return redirect()->route('notes.index');
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
        $note->delete();
        return redirect()->route('notes.index');
    }


    /**
     * Trashed Notes and Notes Files
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $notes = Note::onlyTrashed()->latest()->paginate(10);
        return view('backEnd.notes.SoftDelete', compact('notes'));
    }

    /**
     * Restore Note
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $note = Note::withTrashed()->findOrfail($id);
        $note->restore();
        return redirect()->route('notes.index');
    }

    /**
     * Force Delete Notes
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        $note = Note::withTrashed()->findOrfail($id);
        Storage::disk('local')->delete('public/notes/' . $note->image);
        $note->forceDelete();
        return redirect()->back();
    }

    /**
     * Report Notes
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function report() {
        $notes = Note::selectRaw('count(type) AS cnt, type , user_id')->groupBy('type' , 'user_id')->with('user')->get();

        return view('backEnd.notes.report', compact('notes'));
    }
}
