<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Note;
use App\Models\Notification;
use App\Models\Reading;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TimeRead;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckAccount;

class BookController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', CheckAccount::class]);
    }

  
    public function index()
    {
        $books = Book::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
        $notifications = Notification::where('user_id', Auth::user()->id)->get();  
        $tasks = Task::where('user_id', auth()->user()->id)->get();

        return view('books.index',[
            'books' => $books,
            'search' => 'false',
            'notifications' => $notifications,
            'tasks' => $tasks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request,[
            'title' => 'required',
            'author' => 'required',
            'rating' => 'required',
            'num_page' => 'required',
            'description' =>'required',
            'cover' => 'image|nullable|max:1999',
            'category' => 'required'
        ]);

        if($request->hasFile('cover'))
        {
            $fileNameWithExt = $request->file('cover')->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            $extension = $request->file('cover')->getClientOriginalExtension();

            $fileNameToStore = $fileName.'_'.time().'.'.$extension;

            $path = $request->file('cover')->storeAs('public/cover_images', $fileNameToStore);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }
 
        $request->user()->books()->create([
            'title' => $request->title,
            'author' => $request->author,
            'rating' => $request->rating,
            'num_page' =>$request->num_page,
            'cover' => $fileNameToStore,
            'description' => $request->description,
            'read' => 0,
            'category' => $request->category
       ]);

       return redirect('books');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {       
            $notifications = Notification::where('user_id', Auth::user()->id)->get();  
            $Setting = Setting::where('user_id', Auth::user()->id)->first();  
            $images = DB::table('notes_images')->whereRaw('1 = 1')->paginate(12);

            $book = DB::table('books')->find($id);
            $notes = Note::where('book_id', $id)->orderBy('updated_at', 'desc')->get();
            $tasks = Task::where('book_id', $id)->orderBy('updated_at', 'desc')->get();
            $count_tasks = Task::where('user_id', auth()->user()->id)->where('book_id','=',$id)->count();
            $count_notes = Note::where('user_id', auth()->user()->id)->where('book_id','=',$id)->count();
        //     $reads=Reading::all();
        // if($reads->isEmpty()){
        //     //
        //     }
        //     else{
        //         $reads=Reading::get()->last();
        //     }
            if(auth()->user()->id !== $book->user_id)
            {
                return abort(403, 'Unauthorized action.');
            } 
            return view('books.show',[
                'book' => $book, 
                'notes' => $notes,
                'tasks' => $tasks,
                'count_tasks' =>$count_tasks,
                'count_notes' =>$count_notes,
                // 'reads'=>$reads,
                'notifications' => $notifications,
                'setting' => $Setting,
                'images' => $images
            ]);
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        if(auth()->user()->id !== $book->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 
        return view('books.edit')->with('book', $book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         if($request->hasFile('cover'))
        {
            $fileNameWithExt = $request->file('cover')->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            $extension = $request->file('cover')->getClientOriginalExtension();

            $fileNameToStore = $fileName.'_'.time().'.'.$extension;

            $path = $request->file('cover')->storeAs('public/cover_images', $fileNameToStore);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }
        $book = Book::find($id);
        if(auth()->user()->id !== $book->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 

        if($book->cover != $fileNameToStore && $fileNameToStore != 'noimage.jpg' && $book->cover != 'noimage.jpg') {
            Storage::delete('public/cover_images/'.$book->cover);
        }  
        $this->validate($request, [
            'title' => 'required',
            'author' => 'required',
            'rating' => 'required',
            'num_page' => 'required',
            'description'=>'required'
             
        ]);

        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->rating = $request->input('rating');
        $book->num_page = $request->input('num_page');
        $book->description=$request->input('description');
        if($request->hasFile('cover'))
        {
            $book->cover = $fileNameToStore;
        }
        $book->save();
        return back();
    }


 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $book = Book::find($id);
        if(auth()->user()->id !== $book->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 
        if($book->cover != 'noimage.jpg')
        {
            Storage::delete('public/cover_images/'.$book->cover);
         } 
      
        $book->delete();
         return redirect('books');
    }

    public function read($id)
    {
        $book = Book::find($id);
        if(auth()->user()->id !== $book->user_id)
        {
            return abort(403, 'Unauthorized action.');
        } 
        if($book->read){
            $book->read = 0;
        }
        else 
            if(!$book->read) {
            $book->read = 1;
        }
        $book->save();

    }
    public function search(Request $request)
    {
        $books = Book::where('title', 'like', '%'.$request->title.'%')->get();
        $notifications = Notification::where('user_id', Auth::user()->id)->get();  
        $tasks = Task::where('user_id', auth()->user()->id)->get();

        return view('books.index',[
            'books' => $books,
            'search' => 'true',
            'notifications' => $notifications,
            'tasks' => $tasks
        ]);
    }

}
