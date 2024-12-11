<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:team-list|team-create|team-edit|team-delete', ['only' => ['index','show']]);
         $this->middleware('permission:team-create', ['only' => ['create','store']]);
         $this->middleware('permission:team-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:team-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::get();
        return view('teams.index',compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'ranking' => 'required',
            'colors' => 'required',
            'detail' => 'required',
        ]);

        if ($request->hasFile('media')) {

            if (!file_exists(public_path('images/logos'))) {
                mkdir(public_path('images/logos'), 0777, true);
            }

            $image = $request->file('media');
            $imageName = $image->getClientOriginalName();
            $fileName =  $request->input('name').'_'. time() . '-' . $imageName;
            //Image::make($image)->resize(200,200)->save(public_path('images/logos/'.$fileName));
            Image::make($image)->resize(null, 200, function ($constraint) { $constraint->aspectRatio(); })
                ->save(public_path('images/logos/'.$fileName));

            $request->request->add([
                "logo" => $fileName
            ]);
        }

        Team::create($request->all());

        return redirect()->route('teams.index')
                        ->with('success','team created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return view('teams.show',compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return view('teams.edit',compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        request()->validate([
            'name' => 'required',
            'ranking' => 'required',
            'colors' => 'required',
            'detail' => 'required',
        ]);

        if ($request->hasFile('media')) {
            if (!file_exists(public_path('images/logos'))) {
                mkdir(public_path('images/logos'), 0777, true);
            }
            $image = $request->file('media');
            $imageName = $image->getClientOriginalName();
            $fileName =  $request->input('name').'_'. time() . '-' . $imageName;
            //Image::make($image)->resize(200,200)->save(public_path('images/logos/'.$fileName));
            Image::make($image)->resize(null, 200, function ($constraint) { $constraint->aspectRatio(); })
                ->save(public_path('images/logos/'.$fileName));

            $request->request->add([
                "logo" => $fileName
            ]);
        }

        $team->update($request->all());

        return redirect()->route('teams.index')
                        ->with('success','team updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index')
                        ->with('success','team deleted successfully');
    }
}
