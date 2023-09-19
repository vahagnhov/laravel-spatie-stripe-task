@extends('layouts.email')

@section('content')
    <section>
        <div>
            <h3>@lang('email/texts.hello'), {{ $details['user_name'] }}</h3>
            <p> {{ $details['access'] }}  @lang('email/texts.access_closed_by_admin')</p>
        </div>
    </section>
@endSection
