@extends('layouts/app')

@section('content')

<form action="/profile" method="POST">
  <div class="form-group">
    <label for="profileName">Название группы</label>
    <input type="text" class="form-control" name="name" id="profileName">
    <label for="userId">ID пользователя</label>
    <input type="number" class="form-control" name="user_id" id="userId">
  </div>
  <button type="submit" class="btn btn-primary">Добавить</button>

  {{ method_field('post') }}
  {{ csrf_field() }}
</form>

<hr>

<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Название</th>
      <th scope="col">id Пользователя</th>
      <th scope="col">Действия</th>
    </tr>
  </thead>
  <tbody>
  	@foreach ($profiles as $profile)
    <tr>
      <th data-id="{{ $profile['id'] }}" scope="row">{{ $profile['id'] }}</th>
      
      <td data-id="{{ $profile['id'] }}" contenteditable="true" onkeyup="javascript:undisabled(this,event);">{{ $profile['name'] }}</td>

      <td>{{ $profile['user_id'] }}</td>

      <td>
        <form style="display: inline;" action="/profile/{{ $profile['id'] }}" method="POST">
          <button type="sbumit" class="btn btn-outline-danger btn-sm">Удалить</button>
          {{ method_field('delete') }}
          {{ csrf_field() }}
        </form>

        <form style="display: inline;" action="/profile/{{ $profile['id'] }}" method="POST">
          <input name="name" data-id="{{ $profile['id'] }}" id="name_profile" type="hidden" value="">
          <button type="sbumit" data-id="{{ $profile['id'] }}" class="btn btn-outline-primary btn-sm" disabled >Сохранить</button>
          {{ method_field('patch') }}
          {{ csrf_field() }}
        </form>

      </td>

    </tr>
    @endforeach
  </tbody>
</table>
@endsection

@section('scripts')

<script language="javascript">

function undisabled(element,event)
{
    document.querySelector('form > button[data-id="'+element.getAttribute('data-id')+'"]').disabled = false;
    document.querySelector('form > input[data-id="'+element.getAttribute('data-id')+'"]').value = element.textContent;

    var th = document.querySelector('tr > th[data-id="'+element.getAttribute('data-id')+'"]');
    if (!th.getAttribute('data-changed')){
      th.setAttribute('data-changed', true);
      th.innerText+='*';
    };
};
</script>
@endsection