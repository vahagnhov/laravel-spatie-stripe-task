@extends('layouts.email')

@section('content')
    <section>
        <div>
            <h3>@lang('email/texts.hello'), {{ $details['user_name'] }}</h3>
            <p>@lang('email/texts.your_purchase_successfully_done')</p>
        </div>
    </section>
@endSection
