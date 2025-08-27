@extends('auth.layout')

@section('body')
    <p class="fi-simple-header-subheading mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
        {{ __('auth.snapshot_sign_confirm_message', ['userName' => $user->name]) }}
    </p>
    @if(session('snapshot_sign_login_link_success'))
        {{ __('auth.email_sent') }}
    @endif
    <form method="post" action="{{ $postUrl }}">
        @csrf
        <div class="fi-form-actions">
            <div class="fi-ac gap-3 grid grid-cols-[repeat(auto-fit,minmax(0,1fr))]">
                <x-filament::button type="submit">
                    {{ __('auth.snapshot_sign_confirm_login') }}
                </x-filament::button>
            </div>
        </div>
    </form>
    <p class="fi-simple-header-subheading mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
        {{ __('auth.snapshot_sign_confirm_help_text') }}
    </p>
@endsection
