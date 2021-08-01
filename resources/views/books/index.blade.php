@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список книг</h1>
        <div class='row'>
            <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#addAuthor">
                Добавить книгу
            </button>
        </div>
        <br/>
        <div class='row @if(count($books)!= 0) show @else hidden @endif' id='authors-wrap'>
            <table class="table table-striped ">
                <thead>
                <tr>
                    <th>Название</th>
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
                                <p>{{$author->name}} {{$author->surname}}</p>
                            @endforeach
                        </td>
                        <td>{{ $book->publication }}</td>
                        <td><img src="{{ $book->img }}" alt="" height="75"></td>
                        <td><a href="" class="edit"
                               data-href=" {{ route('books.update',$book->id) }} " data-toggle="modal" data-target="#editAuthor">Изменить</a></td>
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
    </div>

    <!-- Modal -->

    <div class="modal fade" id="addAuthor" tabindex="-1" role="dialog" aria-labelledby="addAuthorLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addAuthorLabel">Добавление автора</h4>
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
                        <label for="edname">Имя</label>
                        <input type="text" class="form-control" id="edname">
                        <span class="text-danger" id="edname-input-error"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edsurname">Фамилия</label>
                        <input type="text" class="form-control" id="edsurname">
                        <span class="text-danger" id="edsurname-input-error"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edpatronymic">Отчество</label>
                        <input type="text" class="form-control" id="edpatronymic">
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
                    },
                    error: function (response) {
                        console.log(response);

                        $('#name-input-error').text(response.responseJSON.errors.name);
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
            changedTr = $(el).children()
            $('#edname').val(changedTr[0].innerText);
            $('#edsurname').val(changedTr[1].innerText);
            $('#edpatronymic').val(changedTr[2].innerText);
            $('#update').data('href', url);
        });

        $('#update').on('click', function () {
            let name = $('#edname').val();
            let surname = $('#edsurname').val();
            let patronymic = $('#edpatronymic').val();
            let url = $(this).data('href');
            $('#name-input-error').text('');
            $('#surname-input-error').text('');
            $.ajax({
                url: url,
                type: "PUT",
                data: {name: name, surname: surname, patronymic: patronymic},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#editAuthor').modal('hide');
                    $('#authors-wrap').removeClass('hidden').addClass('show');
                    $('.alert').removeClass('show').addClass('hidden');
                    changedTr[0].innerText = data['name'];
                    changedTr[1].innerText = data['surname'];
                    changedTr[2].innerText = data['patronymic'];
                },
                error: function (response) {
                    console.log(response);

                    $('#edname-input-error').text(response.responseJSON.errors.name);
                    $('#edsurname-input-error').text(response.responseJSON.errors.surname);
                }
            });
        });
    </script>
@endsection
