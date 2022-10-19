@extends('mails.layout')

@section('content')
    <img src="{{ asset('icon/logo.png') }}" width="300">

    <h3 style="color: #013D52">Bem-vindo ao Organize PDF!</h3>

    <img src="{{ asset('images/mails/welcome.png') }}" width="300">

    <p>Falta pouco para você começar a utilizar o Organiza PDF e simplificar os seus negócios.</p>
    <p>Para isso, confirme o seu e-mail clicando no botão abaixo:</p>

    @include('mails.components.action-button', [
        'title' => ('Confirma'),
        'link'  => $actionLink
    ])

@endsection



