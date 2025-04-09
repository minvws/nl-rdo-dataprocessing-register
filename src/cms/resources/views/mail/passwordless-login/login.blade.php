<x-mail::message>
<div>
{{ __('auth.mail_text') }}:<br>
<x-mail::button :url="$link">{{ __('auth.mail_button_text') }}</x-mail::button>

</div>
</x-mail::message>
