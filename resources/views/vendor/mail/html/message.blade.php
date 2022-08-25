@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => 'https://sead.ufes.br/'])
{{ 'Sead/Ufes' }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ 'Sead/Ufes'/* config('app.name') */ }}. Todos os direitos reservados.{{-- @lang('All rights reserved.') --}}
@endcomponent
@endslot
@endcomponent
