@extends('layouts.email')

@section('content')
    <h1>@lang('email/texts.hello'), {{ $mailData['user_name'] }}</h1>
    <p>@lang('email/texts.your_purchase_successfully_done')</p>
@endSection
