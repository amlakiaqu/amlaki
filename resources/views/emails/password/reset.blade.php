@component('mail::message')
#{{ __('Hello!') }}

{{ __('You are receiving this email because we received a password reset request for your account.') }}

@component('mail::button', ['url' => ''])
{{ __('Reset Password') }}
@endcomponent
<hr />
{{ __('If you did not request a password reset, no further action is required.') }}

{{ __('Regards') }}, <br>
{{ config('app.name') }}
@endcomponent
