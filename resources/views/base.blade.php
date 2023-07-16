@extends('nova-mail-manager::layout.mail')

@section('content')
    {!! $emailTemplate->body !!}
@endsection