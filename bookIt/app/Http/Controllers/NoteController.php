<?php
namespace App\Http\Controllers;

use App\Http\Middleware\CheckAccount;
use App\Models\Note;
use App\Models\Book;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Task;
use CreateNotesTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{

    public function __construct(){
        $this->middleware(['auth', CheckAccount::class]);
    }

    public function index()
    {
         $notifications = Notification::where('user_id', Auth::user()->id)->get();  
        $images = DB::table('notes_images')->whereRaw('1 = 1')->paginate(12);
        $Setting = Setting::where('user_id', Auth::user()->id)->first();  
        $tasks = Task::where('user_id', auth()->user()->id)->get();
        $notes = Note::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
        $books = Book::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();

      

        return view('notes.index',[
            'notes' => $notes,
            'books' =>$books,
            'setting' => $Setting,
            'notifications' => $notifications,
            'tasks' => $tasks,
            'selected' => "All",
            'images' => $images
        ]);
    }

    public function search(Request $request)
    {
    //    dd($request);

        // dd($request->input('word'));
        $notifications = Notification::where('user_id', Auth::user()->id)->get();  
        $images = DB::table('notes_images')->whereRaw('1 = 1')->paginate(12);
 
        $Setting = Setting::where('user_id', Auth::user()->id)->first();  
        if($request->input('word')!=="All")
       {
        $notes = Note::where('user_id', auth()->user()->id)->where('type','LIKE','%'.$request->input('word').'%')->orderBy('updated_at', 'desc')->get();
       }
       else {
        $notes = Note::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();

       }
        $books = Book::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
        $tasks = Task::where('user_id', auth()->user()->id)->get();

        return view('notes.index',[
            'notes' => $notes,
            'books' =>$books,
            'notifications' => $notifications,
            'setting' => $Setting,
            'tasks' => $tasks,
            'selected' => $request->input('word'),
            'images' => $images


        ]);
     }
 
     public function create()
     {
        $books = Book::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();

        return view('notes.create',[
            'books' => $books
        ]);
     }

    public function store(Request $request){
     
         $this->validate($request,[
            'body' => 'required',
            'type' => 'required',
            'titlebook'=>'required',
            'note_images.*' => 'image|nullable|max:1999',
        ]);
        $book = DB::table('books')->where('title',$request->titlebook )->first();

        // if(Membership::where('user_id', Auth::user()->id)->first()->account_type === "free" && !Membership::where('user_id', Auth::user()->id)->first()->end_date)
        // {
        //     if(Note::where('user_id', Auth::user()->id)-> count() > 1)
        //     {
        //         return back()->with('warning', 'You reached your maximum notes ! Try to upgrade your account.');
        //     }
        // }


        if(Membership::where('user_id', Auth::user()->id)->first()->account_type === "free" && !Membership::where('user_id', Auth::user()->id)->first()->end_date)
        {
            if(Note::where('user_id', Auth::user()->id)->whereDate('created_at', '=', date('Y-m-d'))->count() > 30)
            {
                return back()->with('warning', 'You reached your maximum notes per day');
            }
        }
    
        $id =
        $request->user()->notes()->create([
            'body' => $request->body,
            'type'=>$request->type,
            'book_id'=>$book->id
        ])->id;

       $note = Note::find($id);

        if($request->hasFile('note_images'))
        {
            $images = DB::table('notes_images')->where('user_id', Auth::user()->id)->get();
            $sum = 0;
            foreach($images as $image )
            {
               $sum = $sum + Storage::size('public/notes_images/'.$image->image);
            }
            if($sum>1000000000 &&  Membership::where('user_id', Auth::user()->id)->first()->account_type === "free" && !Membership::where('user_id', Auth::user()->id)->first()->end_date)
            {
                return back()->with('max_size', 'You reached already 1 GB of notes images!');
            }
            else if($sum>100000000000 &&  Membership::where('user_id', Auth::user()->id)->first()->account_type === "premium" || ( Membership::where('user_id', Auth::user()->id)->first()->account_type === "free" && Membership::where('user_id', Auth::user()->id)->first()->end_date))
            {
                return back()->with('max_size', 'You reached already 10 GB of notes images!');
            }
            foreach($request->file('note_images') as $file)
            {
                 $fileNameWithExt = $file->getClientOriginalName();

                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    
                $extension = $file->getClientOriginalExtension();
    
                $fileNameToStore = $fileName.'_'.time().'.'.$extension;
    
                $path = $file->storeAs('public/notes_images', $fileNameToStore);
    
                DB::table('notes_images')->insert([
                    'note_id' => $note->id,
                    'user_id' => Auth::user()->id,
                    'image' =>$fileNameToStore,
                ]);
            }
           
        }
     

        return redirect('notes');
  
    }
    public function show($id){
        $note = DB::table('notes')->find($id);
        $images = DB::table('notes_images')->where('note_id', $id)->paginate(10);

         if(auth()->user()->id !== $note->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 
        return view('notes.show',[
            'note' => $note,
            'images' => $images
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        
        $note = Note::find($id);
        if(auth()->user()->id !== $note->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 

        $this->validate($request, [
            'body'.$id => 'required',
            'type' => 'required',
            'note_images.*' => 'image|nullable|max:1999',
        ]);

        

        $note = Note::find($id);  
        $note->body = $request->input('body'.$id );
        $note->type = $request->input('type');
        if($request->hasFile('note_images'))
        {
            $images = DB::table('notes_images')->where('user_id', Auth::user()->id)->get();
            $sum = 0;
            foreach($images as $image )
            {
               $sum = $sum + Storage::size('public/notes_images/'.$image->image);
            }
            if($sum>1000000000 &&  Membership::where('user_id', Auth::user()->id)->first()->account_type === "free" && !Membership::where('user_id', Auth::user()->id)->first()->end_date)
            {
                return back()->with('max_size', 'You reached already 1 GB of notes images!');
            }
            else if($sum>100000000000 &&  Membership::where('user_id', Auth::user()->id)->first()->account_type === "premium" || ( Membership::where('user_id', Auth::user()->id)->first()->account_type === "free" && Membership::where('user_id', Auth::user()->id)->first()->end_date))
            {
                return back()->with('max_size', 'You reached already 10 GB of notes images!');
            }
            foreach($request->file('note_images') as $file)
            {
                 $fileNameWithExt = $file->getClientOriginalName();

                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    
                $extension = $file->getClientOriginalExtension();
    
                $fileNameToStore = $fileName.'_'.time().'.'.$extension;
    
                $path = $file->storeAs('public/notes_images', $fileNameToStore);
    
                DB::table('notes_images')->insert([
                    'note_id' => $note->id,
                    'user_id' => Auth::user()->id,
                    'image' =>$fileNameToStore,
                ]);
            }
           
        }
    

        $note->save();

        return redirect('notes');
    }


    public function destroy($id)
    {
        $note = Note::find($id);
      if(auth()->user()->id !== $note->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 
        $note->delete();
         return redirect('notes');
    }

    public function edit($id)
    {
        $note = Note::find($id);

        $images = DB::table('notes_images')->where('note_id', $id)->paginate(12);

        if(auth()->user()->id !== $note->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 
        return view('notes.edit')->with([
            'note'=> $note,
            'images' => $images
        ]);
    }

    public function deleteImage($id)
    {
        $image =  DB::table('notes_images')->where('id', $id);
          if(auth()->user()->id !== $image->first()->user_id)
          {
              return abort(403, 'Unauthorized action.');
          } 
          $image->delete();
           return back();
    }

}
