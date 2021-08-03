<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        if ($request->order){
            $order = $request->order;
        } else {
            $order = 'asc';
        }

        if ($request){
            $authors = Author::where('name', 'LIKE', '%'.$request->search.'%')->orWhere('surname', 'LIKE', '%'.$request->search.'%')->orderBy('surname', $order)->paginate(15);
        }else{
            $authors = Author::orderBy('surname', $order)->paginate(15);
        }

        return view('authors.index', [
            'authors' => $authors,
            'order' => $order
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
     * @return array
     * @throws ValidationException
     */
    public function store(Request $request): array
    {
        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required|min:3',
        ]);

        $res = Author::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic
        ]);

        return [
            'id' => $res->id,
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param Author $author
     * @return Author
     */
    public function show(Author $author)
    {
        return $author;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Author $author)
    {
        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required|min:3',
        ]);

        $author->name = $request->name;
        $author->surname = $request->surname;
        $author->patronymic = $request->patronymic;
        $author->save();

        return [
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Author $author
     * @return Response
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return 'ok';
    }

}
