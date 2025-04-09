@php
    /** @var App\Models\Organisation $organisation */
@endphp
{!! $frontmatter !!}

<h2>{{ __('public_website.content.privacy_statement') }} {{ $organisation->name }}</h2>

{!! $content !!}
