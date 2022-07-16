<?php

namespace App\Http\Controllers\Book;

use App\Constants\ResponseCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Exception;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $book= Book::all();
        // dd($book);
        return response()->json([$book], ResponseCode::SUCESS);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {
        $validator= Validator::make($request->all(),[
            'title'=>'required|max:30|min:3',
            'author'=>'required',
            'quantity'=>'required'
        ]);

        if ($validator->fails()){
            $error =$validator->errors();
            return response()->json(['data'=>$error],ResponseCode::VALIDATION_ERROR);

        }
        try{
            $book = New Book();
            $book->title=$request->title;
            $book->author=$request->author;
            $book->quantity=$request->quantity;
            $book->save();
        }catch(Exception $e){
            return response()->json(["error"=> $e->getMessage()], ResponseCode::SERVER_ERROR);

        }
        return response()->json(['data'=>$book,'message'=>'Book Sucessfully Added'], ResponseCode::SUCESS);

        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $book = Book::findorfail($id);
            return response()->json(['data'=>$book], ResponseCode::SUCESS);
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()], ResponseCode::SERVER_ERROR);
        }
    
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
        try{
        $book = Book::find($id);
        $book->title=$request->title;
        $book->author=$request->author;
        $book->quantity=$request->quantity;
        $book->save();
        return response()->json(['data'=>$book,'message'=>'Book Sucessfully Updated'], ResponseCode::SUCESS);
        }catch(Exception $e){
        return response()->json(["error"=> $e->getMessage()], ResponseCode::SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Book::findorfail($id)->delete();
            return response()->json(['message'=>'Deleted Sucessfully'], ResponseCode::SUCESS);

        }catch(Exception $e){
            return response()->json(["error"=> $e->getMessage()], ResponseCode::SERVER_ERROR);
    
        }
    }
}
