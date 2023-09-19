@extends('layouts.email')

@section('content')
    <h1>@lang('email/texts.hello'), {{ $mailData['user_name'] }}</h1>
    <p> {{ $mailData['access'] }} >@lang('email/texts.access_closed_by_admin')</p>
@endSection
