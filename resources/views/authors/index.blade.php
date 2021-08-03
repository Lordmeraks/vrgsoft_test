@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список авторов</h1>
        <div class='row'>
            <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal"
                    data-target="#addAuthor">
                Добавить автора
            </button>
        </div>
        <br/>
        <form action="{{route('authors.index')}}" method="get">
            <input type="search" name="search">
            <input type="submit" value="поиск">
        </form>
        <div class='row @if(count($authors)!= 0) show @else hidden @endif' id='authors-wrap'>
            <table class="table table-striped ">
                <thead>
                <tr>
                    <th>Имя</th>
                    @if($order == 'asc')
                        <th><a href="{{route('authors.index', 'order=desc')}}">Фамилия</a></th>
                    @else
                        <th><a href="{{route('authors.index', 'order=asc')}}">Фамилия</a></th>
                    @endif
                    <th>Отчество</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->surname }}</td>
                        @if(!is_null($author->patronymic))
                            <td>{{ $author->patronymic }}</td>
                        @else
                            <td></td>
                        @endif
                        <td><a href="" class="edit"
                               data-href=" {{ route('authors.update',$author->id) }} " data-toggle="modal"
                               data-target="#editAuthor">Изменить</a></td>
                        <td><a href="" class="delete"
                               data-href=" {{ route('authors.destroy',$author->id) }} ">Удалить</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="alert alert-warning @if(count($authors) != 0) hidden @else show @endif" role="alert"> Записей
                нет
            </div>
        </div>
        {{ $authors->links() }}
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
                        <label for="name">Имя</label>
                        <input type="text" class="form-control" id="name">
                        <span class="text-danger" id="name-input-error"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="surname">Фамилия</label>
                        <input type="text" class="form-control" id="surname">
                        <span class="text-danger" id="surname-input-error"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="patronymic">Отчество</label>
                        <input type="text" class="form-control" id="patronymic">
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
                    <h4 class="modal-title" id="editAuthorLabel">Редактировать автора</h4>
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
                let name = $('#name').val();
                let surname = $('#surname').val();
                let patronymic = $('#patronymic').val();
                $('#name-input-error').text('');
                $('#surname-input-error').text('');
                $.ajax({
                    url: '{{ route('authors.store') }}',
                    type: "POST",
                    data: {name: name, surname: surname, patronymic: patronymic},
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#addAuthor').modal('hide');
                        $('#authors-wrap').removeClass('hidden').addClass('show');
                        $('.alert').removeClass('show').addClass('hidden');
                        if (data['patronymic'] == null) {
                            data['patronymic'] = '';
                        }
                        let str = '<tr><td>' + data['name'] +
                            '</td><td>' + data['surname'] +
                            '</td><td>' + data['patronymic'] +
                            '<td><a href="" class="edit" data-href="/authors/' + data['id'] + '" data-toggle="modal" data-target="#editAuthor">Изменить</a></td>' +
                            '</td><td><a href="" class="delete" data-href="/authors/' + data['id'] + '">Удалить</a></td></tr>';
                        $('.table > tbody:last').append(str);
                    },
                    error: function (response) {
                        console.log(response);

                        $('#name-input-error').text(response.responseJSON.errors.name);
                        $('#surname-input-error').text(response.responseJSON.errors.surname);
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
