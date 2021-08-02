<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $authors = Author::all();
        $books = Book::all();
        return view('books.index', [
            'authors' => $authors,
            'books' => $books
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'publication' => 'required',
            'authors' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = self::upload($request);

        $book = Book::create([
            'name' => $request->name,
            'description' => $request->description,
            'img' => '/storage/' . $path,
            'publication' => $request->publication
        ]);

        $authors = $request->authors;
        $authors = explode(',', $authors);

        $book->authors()->attach($authors);

        $htmlAuthors = '';
        foreach ($book->authors as $author){
            $htmlAuthors .= "<p name='$author->id'>$author->name $author->surname</p>";
        }

        $book->htmlAuthors = $htmlAuthors;

        return $book;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Book $book)
    {
        $this->validate($request, [
            'name' => 'required',
            'publication' => 'required',
            'authors' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $book->name = $request->name;
        $book->description = $request->description;
        if ($request->hasFile('file')) {
            $path = self::upload($request);
            $book->img = '/storage/' . $path;
        }
        $book->publication = $request->publication;
        $book->save();

        $authors = $request->authors;
        $authors = explode(',', $authors);

        $book->authors()->detach();
        $book->authors()->attach($authors);

        $htmlAuthors = '';
        foreach ($book->authors as $author){
            $htmlAuthors .= "<p name='$author->id'>$author->name $author->surname</p>";
        }

        $book->htmlAuthors = $htmlAuthors;

        return $book;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return 'ok';
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public static function upload(Request $request)
    {
        return $request->file('file')->store('img', 'public');
    }
}
