@extends('layouts.app')
@section('content') 
    <div class="container-fluid">
        <div class="row">
            @include('notes.layouts.sidebar')
            <style>
                      ::placeholder{
                    font-weight: 600;
                    font-size: 16px;
                    line-height: 20px;
                }
                </style>
            <div class="col">
                <div class="container py-3">
                    <div class="d-flex flex-row">
                        <p style="font-weight:700; font-size:30px;">
                            Create Book
                        </p>  
                        <div class="ml-auto mr-0"  style="width: 45px; height:45px; border-radius:50%;background:#000;">
                            <img src="images/about_img.svg" alt="" style="max-width:100%;
                            max-height:100%; ">
                        </div>
                    </div>
                @error('cover')
                 <p> {{$message}} </p>   
                @enderror
                    <hr style="border-top: 1px solid #00000023;">
                    <form action="{{route('books.update', $book->id)}}" method="post" enctype="multipart/form-data"  >
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="col-md-12">
                                 <div class="card" style="border-radius:30px; border:0;">
                                    <div class="form-group">
                                        <input type="text" name="title" class="form-control py-4 py-4" value="{{ $book->title}}" style="border-radius:10px; background: #E4F1FF;" placeholder="Title">
                                      </div>
                                   </div>
                                </div>
                             </div>  
                              
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                     <div class="card" style="border-radius:30px; border:0;">
                                        <div class="form-group">
                                            <input type="text" name="author" class="form-control py-4 py-4" value="{{ $book->author}}"  style="border-radius:10px; background: #E4F1FF;" placeholder="author">
                                          </div>
                                       </div>
                                    </div>
                                 </div>  
                                 <div class="col-md-3">
                                    <div class="col-md-12">
                                     <div class="card" style="border-radius:30px; border:0;">
                                        <div class="form-group">
                                            <input type="text" name="rating" value={{$book->rating}} class="form-control py-4 py-4"  style="border-radius:10px; background: #E4F1FF;" placeholder="rating">
                                          </div>
                                       </div>
                                    </div>
                                 </div> 

                             <div class="col-md-2">
                                <div class="col-md-12">
                                     <div class="form-group">
                                        <select class="custom-select">
                                            <option disabled>Category</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                          </select>
                                      </div>
                                   </div>
                              </div> 

                             
                                
                       
                          </div>
                          <div class="row">
                            <div class="col-md-3">
                                <div class="col-md-12">
                                 <div class="card" style="border-radius:30px; border:0;">
                                    <div class="form-group">
                                        <input type="text" name="num_page" value="{{ $book->num_page}}" class="form-control py-4 py-4"  style="border-radius:10px; background: #E4F1FF;" placeholder="number of pages">
                                      </div>
                                   </div>
                                </div>
                             </div> 
                             <div class="col-md-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" name="cover" value="{{$book->cover}}" >
                                    <label class="custom-file-label" for="customFile">Choose image</label>hhh{{$book->cover}}kkk
                                  </div>
                             </div>
                            
                             <div style="margin-top: 350px;margin-left:34%"><button type="submit"  name="create" class="btn  btn-lg  btn-primary"
                                style="background-color:#1F1A6B;font-weight:600;font-size:22px; border-radius:12px;   "
                                onclick="javascript:;" >Create</button></div>
                          </div>
                       
                    </form>
                    
                         
                </div>

   
            </div>

        </div>
                    
    </div>
@endsection