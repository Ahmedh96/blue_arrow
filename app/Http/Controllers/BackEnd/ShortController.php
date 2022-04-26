<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Short;
use Illuminate\Http\Request;

class ShortController extends Controller
{
    /**
     * Display the specified note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function share($id)
    {
        $note = Note::with('user')->findOrfail($id);
        return view('backEnd.notes.show', compact('note'));
    }
}
