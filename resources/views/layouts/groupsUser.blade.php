@extends('layouts/app')

@section('content')

<form action="/group" method="POST">
  <div class="form-group">
    <label for="groupName">Название группы</label>
    <input type="text" class="form-control" name="name" id="groupName">
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
      <th scope="col">Действия</th>
    </tr>
  </thead>
  <tbody>
  	@foreach ($groups as $group)
    <tr>
	  <th scope="row">{{ $group['id'] }}</th>
	  <td>{{ $group['name'] }}</td>
		  <td>
	      	<form action="/group/{{ $group['id'] }}" method="POST">
				<button type="submit" class="btn btn-outline-danger btn-sm">Удалить</button>
				{{ method_field('delete') }}
    			{{ csrf_field() }}
			</form>
		  </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection