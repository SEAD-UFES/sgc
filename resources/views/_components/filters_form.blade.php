<div style="border:1px solid black; padding:1px; display: flex; flex-wrap: wrap; align-items: center;">
                
    <div style="flex-grow: 1;">
        <span>Filtros: </span>
        
        @foreach ($options as $option)
            @if( $filters && array_key_exists( $option['value'], $filters ) )
                @if( is_string( $filters[$option['value']] ) )
                    <a href="#" onclick="removeFilter('{{$option['value']}}', null)">({{ $option['label'] }}: {{$filters[$option['value']]}})</a>
                @elseif( is_array( $filters[$option['value']]) )
                    @foreach ($filters[$option['value']] as $key => $filter)
                        <a href="#" onclick="removeFilter('{{$option['value']}}', {{$key}})">({{$option['label'] }}: {{$filters[$option['value']][$key]}})</a>
                    @endforeach
                @endif
            @endif
        @endforeach

    </div>

    <div style="flex-grow: 1; display: flex; justify-content: flex-end;">
        <form onsubmit="submitFilters(event)">
            <select id="filterType" name='type'>
                @foreach ($options as $option)
                    <option 
                        value="{{$option['value']}}" 
                        {{ array_key_exists( 'selected', $option ) && $option['selected'] === true ? 'selected' : '' }}
                    >{{$option['label']}}</option>
                @endforeach
            </select>
            <input id="filterValue" type="text" name='value' />
            <button type="submit">Filtrar</button>
        </form>
    </div>

</div>