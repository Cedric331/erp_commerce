@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url'), 'category' => $category ?? 'Votre compte'])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
@endcomponent
@endslot
@endcomponent
