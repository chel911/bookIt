<?php

namespace App\Http\Controllers;
use App\Http\Middleware\CheckAccount;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function __construct(){
        $this->middleware(['auth', CheckAccount::class]);
    }

    public function index()
    {
        return view('profile');
    }
    public function store(Request $request){
          $this->validate($request, [
            'fName' => 'required|max:255',
            'lName' => 'required|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required',
            'profile_image' => 'image|nullable|max:1999'
        ]);

        if(Auth::user()->email !== $request->email)
        {
            $this->validate($request, [
              
                'email' => 'unique:App\Models\User,email',
  
            ]);
        }

        if(Auth::user()->username !== $request->username)
        {
            $this->validate($request, [
              
                 'username' => 'unique:App\Models\User,username',
 
            ]);
        }



        if($request->hasFile('profile_image'))
        {
            $fileNameWithExt = $request->file('profile_image')->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            $extension = $request->file('profile_image')->getClientOriginalExtension();

            $fileNameToStore = $fileName.'_'.time().'.'.$extension;

            $path = $request->file('profile_image')->storeAs('public/profile_images', $fileNameToStore);
        }else {
            $fileNameToStore = Auth::user()->profile_image;
        }



        $count = User::where('email', $request->email)->count();
        if($count && auth()->user()->email!==$request->email){
            return back()->with('error', 'email already exists');
        }
        if(auth()->user()->email===$request->email 
        && auth()->user()->firstName===$request->fName 
        &&auth()->user()->lastName===$request->lName 
        && auth()->user()->username===$request->username 
        && $fileNameToStore === Auth::user()->profile_image){
            return back();
        }

        if(Auth::user()->profile_image != $fileNameToStore && Auth::user()->profile_image != 'no_image.png') {
            Storage::delete('public/profile_images/'.Auth::user()->profile_image );
        }  

        User::find(auth()->user()->id)->update([
            'firstName'=> $request->fName,
            'lastName'=> $request->lName,
            'email'=> $request->email,
            'username' => $request->username,
            'profile_image' => $fileNameToStore
            ]
        );
        return redirect('profile')->with('success', 'profile has been changed successfully');

    }

    public function deletePicture($id)
    {
        $image =  User::find($id)->profile_image;
           if(auth()->user()->id !== User::find($id)->id)
          {
              return abort(403, 'Unauthorized action.');
          } 
          if($image !== "no_image.png")
          {
            Storage::delete('public/profile_images/'.Auth::user()->profile_image );
            User::find(auth()->user()->id)->update([
              
                'profile_image' => "no_image.png"
                ]
            );
          }
           return redirect('profile');
    }
}
