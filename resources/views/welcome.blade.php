@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Книги и Авторы</h1>
        <div class='row'>
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg pull-right">
                Книги
            </a>
            <a href="{{ route('authors.index') }}" class="btn btn-primary btn-lg pull-right">
                Авторы
            </a>
        </div>
        <br/>


    </div>



@endsection
@section('ajaxScript')

@endsection
