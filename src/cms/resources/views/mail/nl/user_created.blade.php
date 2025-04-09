<x-mail::message>
<h1>{{ __('email.user_created.heading') }}</h1>

<p>{{ __('email.user_created.text') }}</p>

<p><x-mail::button :url="$link">{{ __('email.user_created.button_text') }}</x-mail::button></p>
</x-mail::message>
