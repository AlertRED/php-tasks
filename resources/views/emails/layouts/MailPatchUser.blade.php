@extends('emails/layouts/MailBase')

@section('title')

@endsection

@section('content')
Пользователь с email {{ $email }} обновил информацию пользователя с id: {{ $newUser['id'] }}

@if ($oldUser['name'] !== $newUser['name'])
Имя: {{ $oldUser['name'] }} => {{ $newUser['name'] }}
@endif
@if ($oldUser['role'] !== $newUser['role'])
Роль: {{ $oldUser['role'] }} => {{ $newUser['role'] }} 
@endif
@if (!$oldUser['banned'] && $newUser['banned'])
<br>
<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTi0jwfewRar7KBHWui5WmDGhNo2ZkeqkD8LlgWs0xucTNfDmEFXw">
@endif
@endsection