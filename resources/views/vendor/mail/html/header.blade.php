<tr>
<td class="header" style="padding-bottom: 0em">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Sead/Ufes')
<img src="{{ asset('SeadUFES_Horizontal azul.png') }}" class="content-cell" style="width: 25em; padding-bottom: 0em" alt="Sead Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
