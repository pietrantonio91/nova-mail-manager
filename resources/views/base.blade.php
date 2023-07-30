@extends('nova-mail-manager::layout.mail')

@section('content')
    {!! $emailTemplate->getFormattedBody($variables) !!}
@endsection