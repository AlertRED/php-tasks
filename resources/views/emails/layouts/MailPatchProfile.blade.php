@extends('emails/layouts/MailBase')

@section('title')

@endsection

@section('content')
Обновился профиль с id: {{ $id }}:
<br>
 {{ $oldName }} => {{ $newName }}
@endsection