@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# Bonjour,
@endif

{{-- Content --}}
{!! $content !!}

{{-- Action Button --}}
@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
{{ $actionText }}
@endcomponent
@endisset

{{-- Additional Content --}}
@isset($additionalContent)
{!! $additionalContent !!}
@endisset

{{-- Salutation --}}
Cordialement,<br>
L'équipe {{ config('app.name') }}
@endcomponent
