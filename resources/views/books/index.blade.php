@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список книг</h1>
        <div class='row'>
            <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal"
                    data-target="#addAuthor">
                Добавить книгу
            </button>
        </div>
        <br/>
        <form action="{{route('books.index')}}" method="get">
            <input type="search" name="search">
            <input type="submit" value="поиск">
        </form>
        <div class='row @if(count($books)!= 0) show @else hidden @endif' id='authors-wrap'>
            <table class="table table-striped ">
                <thead>
                <tr>
                    @if($order == 'asc')
                        <th><a href="{{route('books.index', 'order=desc')}}">Название</a></th>
                    @else
                        <th><a href="{{route('books.index', 'order=asc')}}">Название</a></th>
                    @endif
                        <th>Описание</th>
                        <th>Авторы</th>
                        <th>Дата публикации</th>
                        <th>Обложка</th>
                        <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->name }}</td>
                        @if(!is_null($book->description))
                            <td>{{ $book->description }}</td>
                        @else
                            <td></td>
                        @endif
                        <td>
                            @foreach($book->authors as $author)
                                <p name="{{$author->id}}">{{$author->name}} {{$author->surname}}</p>
                            @endforeach
                        </td>
                        <td>{{ $book->publication }}</td>
                        <td><img src="{{ $book->img }}" alt="" height="75"></td>
                        <td><a href="" class="edit"
                               data-href=" {{ route('books.update',$book->id) }} " data-toggle="modal"
                               data-target="#editAuthor">Изменить</a></td>
                        <td><a href="" class="delete"
                               data-href=" {{ route('books.destroy',$book->id) }} ">Удалить</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="alert alert-warning @if(count($books) != 0) hidden @else show @endif" role="alert"> Книг
                нет
            </div>
        </div>
        {{ $books->links() }}
    </div>



    <!-- Modal -->

    <div class="modal fade" id="addAuthor" tabindex="-1" role="dialog" aria-labelledby="addAuthorLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addAuthorLabel">Добавление Книги</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" class="form-control" id="name">
                        <span class="text-danger" id="name-input-error"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea class="form-control" id="description"></textarea>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="publication">Дата публикации</label>
                        <input type="date" class="form-control" id="publication">
                        <span class="text-danger" id="publication-input-error"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="authors">Авторы - выберете авторов книги</label>
                        <select multiple class="form-control" id="authors" name="authors[]">
                            @foreach($authors as $author)
                                <option value="{{$author->id}}">{{$author->name}} {{$author->surname}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="authors-input-error"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="image">Обложка</label>
                        <input type="file" class="form-control" id="image">
                        <span class="text-danger" id="image-input-error"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="save">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAuthor" tabindex="-1" role="dialog" aria-labelledby="editAuthorLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editAuthorLabel">Редактировать книгу</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" class="form-control" id="ed-name">
                        <span class="text-danger" id="ed-name-input-error"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea class="form-control" id="ed-description"></textarea>
                        <span class="text-danger" id="ed-description-input-error"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="publication">Дата публикации</label>
                        <input type="date" class="form-control" id="ed-publication">
                        <span class="text-danger" id="ed-publication-input-error"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="authors">Авторы - выберете авторов книги</label>
                        <select multiple class="form-control" id="ed-authors" name="authors[]">
                            @foreach($authors as $author)
                                <option value="{{$author->id}}"
                                        class="option">{{$author->name}} {{$author->surname}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="ed-authors-input-error"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="ed-cur-image">Текущая обложка:</label>
                        <div id="ed-cur-image"></div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="image">Обложка</label>
                        <input type="file" class="form-control" id="ed-image">
                        <span class="text-danger" id="ed-image-input-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="update">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('ajaxScript')
    <script>
        $(function () {
            $('#save').on('click', function () {
                let form_data = new FormData();
                form_data.append('file', $('#image').prop('files')[0]);
                form_data.append('name', $('#name').val());
                form_data.append('description', $('#description').val());
                form_data.append('publication', $('#publication').val());
                form_data.append('authors', $('#authors').val());
                $('#name-input-error').text('');
                $('#image-input-error').text('');
                $('#authors-input-error').text('');
                $('#publication-input-error').text('');
                $.ajax({
                    url: '{{ route('books.store') }}',
                    type: "POST",
                    contentType: false,
                    processData: false,
                    data: form_data,
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#addAuthor').modal('hide');
                        $('#authors-wrap').removeClass('hidden').addClass('show');
                        $('.alert').removeClass('show').addClass('hidden');
                        console.log(data);
                        if (data['patronymic'] == null) {
                            data['patronymic'] = '';
                        }
                        let str = '<tr><td>' + data.name +
                            '</td><td>' + data.description +
                            '</td><td>' + data.htmlAuthors +
                            '</td><td>' + data.publication +
                            '</td><td>' + '<img src=' + data.img + ' alt="" height="75">' +
                            '<td><a href="" class="edit" data-href="/books/' + data.id + '" data-toggle="modal" data-target="#editAuthor">Изменить</a></td>' +
                            '</td><td><a href="" class="delete" data-href="/books/' + data.id + '">Удалить</a></td></tr>';
                        $('.table > tbody:last').append(str);
                    },
                    error: function (response) {
                        console.log(response);

                        $('#name-input-error').text(response.responseJSON.errors.name);
                        $('#authors-input-error').text(response.responseJSON.errors.authors);
                        $('#publication-input-error').text(response.responseJSON.errors.publication);
                        $('#image-input-error').text(response.responseJSON.errors.file);
                    }
                });
            });
        })

        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            let url = $(this).data('href');
            let el = $(this).parents('tr');
            $.ajax({
                url: url,
                type: "DELETE",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    el.detach();
                },
                error: function (msg) {
                    alert('Ошибка');
                }
            });
        });

        let changedTr;

        $('.edit').on('click', function (e) {
            e.preventDefault();
            let el = $(this).parents('tr');
            let url = $(this).data('href');
            changedTr = $(el).children();
            $('#ed-name').val(changedTr[0].innerText);
            $('#ed-description').val(changedTr[1].innerText);
            let authors = changedTr[2].children;
            let options = $('.option');
            for (let i = 0; i < authors.length; i++) {
                let author_id = authors[i].attributes.name.value;
                for (let j = 0; j < options.length; j++) {
                    let $optval = $(options[j]);
                    if ($optval.val() === author_id) {
                        $optval.prop('selected', true);
                    }
                }
            }
            $('#ed-publication').val(changedTr[3].innerText);
            $('#ed-cur-image').html(changedTr[4].innerHTML);
            $('#update').data('href', url);
        });

        $('#update').on('click', function () {
            let data = new FormData();
            console.log($('#ed-image').prop('files')[0]);
            if ($('#ed-image').prop('files')[0] !== undefined) {
                data.append('file', $('#ed-image').prop('files')[0]);
            }
            data.append('name', $('#ed-name').val());
            data.append('description', $('#ed-description').val());
            data.append('publication', $('#ed-publication').val());
            data.append('authors', $('#ed-authors').val());
            data.append('_method', 'PUT');
            $('#ed-name-input-error').text('');
            $('#ed-image-input-error').text('');
            $('#ed-authors-input-error').text('');
            $('#ed-publication-input-error').text('');
            let url = $(this).data('href');
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#editAuthor').modal('hide');
                    $('#authors-wrap').removeClass('hidden').addClass('show');
                    $('.alert').removeClass('show').addClass('hidden');
                    console.log(data);
                    changedTr[0].innerText = data.name;
                    changedTr[1].innerText = data.description;
                    changedTr[2].innerHTML = data.htmlAuthors;
                    changedTr[3].innerHTML = data.publication;
                    changedTr[4].innerHTML = '<img src=' + data.img + ' alt="" height="75">';
                },
                error: function (response) {
                    console.log(response);

                    $('#ed-name-input-error').text(response.responseJSON.errors.name);
                    $('#ed-authors-input-error').text(response.responseJSON.errors.authors);
                    $('#ed-publication-input-error').text(response.responseJSON.errors.publication);
                    $('#ed-image-input-error').text(response.responseJSON.errors.file);
                }
            });
        });
    </script>
@endsection
