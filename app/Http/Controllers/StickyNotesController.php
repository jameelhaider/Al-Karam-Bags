<?php

namespace App\Http\Controllers;

use App\Models\StickyNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StickyNotesController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = StickyNotes::query()
                ->select('sticky_notes.title as title', 'sticky_notes.content as content', 'sticky_notes.id as id', 'sticky_notes.is_pinned as is_pinned');
            if ($request->title) {
                $query->where('sticky_notes.title', 'LIKE', '%' . $request->title . '%');
            }
            $stickynotes = $query
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'asc')
                ->paginate(100);

            return view('stickynotes.index', compact('stickynotes'));
        } else {
            return abort(401);
        }
    }





    public function pin($id)
    {
        if (Gate::allows('is_admin')) {
            $stickynote = StickyNotes::findOrFail($id);
            $stickynote->is_pinned = true; // Set the pinned status to true
            $stickynote->save();

            return redirect()->back()->with('success', 'Sticky note pinned successfully.');
        } else {
            return abort(401);
        }
    }

    public function unpin($id)
    {
        if (Gate::allows('is_admin')) {
            $stickynote = StickyNotes::findOrFail($id);
            $stickynote->is_pinned = false; // Set the pinned status to false
            $stickynote->save();

            return redirect()->back()->with('success', 'Sticky note unpinned successfully.');
        } else {
            return abort(401);
        }
    }



    public function create()
    {
        if (Gate::allows('is_admin')) {
            $stickynote = new StickyNotes();
            return view("stickynotes.create", compact('stickynote'));
        } else {
            return abort(401);
        }
    }


    public function edit($id)
    {
        if (Gate::allows('is_admin')) {
            $stickynote = StickyNotes::find($id);
            if (!$stickynote)
                return redirect()->back();
            return view("stickynotes.create", compact('stickynote'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $stickynote = new StickyNotes();
            $stickynote->title = $request->title;
            $stickynote->content = $request->content;
            $stickynote->is_pinned = $request->has('is_pinned') ? 1 : 0;
            $stickynote->save();
            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Sticky Note Added! You can add another one.');
            }
            return redirect()->route('index.stickynote')->with('success', 'Sticky Note Added Successfully!');
        } else {
            return abort(401);
        }
    }





    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $stickynote = StickyNotes::findOrFail($id);
            $stickynote->title = $request->title;
            $stickynote->content = $request->content;
            $stickynote->is_pinned = $request->has('is_pinned') ? 1 : 0;
            $stickynote->save();
            return redirect()->route('index.stickynote')->with('success', 'Sticky Note Updated Successfully!');
        } else {
            return abort(401);
        }
    }






    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $company = StickyNotes::find($id);
            if (!is_null($company)) {
                $company->delete();
            }
            return redirect()->back()->with('success', 'Note Deleted Successfully');
        } else {
            return abort(401);
        }
    }
}
