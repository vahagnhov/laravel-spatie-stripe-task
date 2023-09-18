@extends('layouts.email')

@section('content')
    <h1>Hello, {{ $mailData['user_name'] }}</h1>
    <p>Your {{ $mailData['access'] }} is closed by the administrator!</p>
@endSection
