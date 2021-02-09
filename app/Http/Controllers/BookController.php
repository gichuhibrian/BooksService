<?php

namespace App\Http\Controllers;

use App\Book;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $books = Book::all();
        return $this->successResponse($books);
    }

    public function store(Request $request)
    {
        $this->validation($request);
        $book = Book::create($request->all());
        return $this->successResponse($book, Response::HTTP_CREATED);

    }

    public function show($book)
    {
        $book = Book::findOrFail($book);
        return $this->successResponse($book);
    }

    public function update(Request $request, $book)
    {
        $this->validation($request);
        $book = Book::findOrFail($book);

        $book->fill($request->all());
        if($book->isClean())
        {
            return $this->errorResponse('At least one value should change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $book->save();
        return $this->successResponse($book);
    }

    public function destroy($book)
    {
        $book = Book::findOrFail($book);
        $book->delete();
        return $this->successResponse($book);
    }

    private function validation($data)
    {
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|integer|min:1',
            'author_id' => 'required|min:1'
        ];
        $this->validate($data, $rules);
    }
}
