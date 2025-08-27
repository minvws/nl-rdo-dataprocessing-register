<x-mail::message>
<div>
    {{ __('auth.passwordless_login_text') }}:<br>
    <x-mail::button :url="$link">{{ __('auth.passwordless_login_button_text') }}</x-mail::button>
</div>
</x-mail::message>
