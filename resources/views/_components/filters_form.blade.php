<div class="container-fluid p-1 mb-2">
    <div class="row px-3 mb-1">
        <div class="col-sm px-1 d-flex align-items-center">
            <span>Filtros:</span>
        </div>
        <div class="col-sm-auto px-1 ms-auto d-flex justify-content-center">
            <form class="row"  onsubmit="submitFilters(event)">
                <div class="col-auto px-0 mx-1">
                    <select class="form-select form-select-sm" id="filterType" name='type'>
                        @foreach ($options as $option)
                            <option 
                                value="{{$option['value']}}" 
                                {{ array_key_exists( 'selected', $option ) && $option['selected'] === true ? 'selected' : '' }}
                            >{{$option['label']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto px-0 mx-1">
                    <input class="form-control form-control-sm" id="filterValue" type="text" name='value' placeholder="Pesquisar" />
                </div>

                <div class="col-auto px-0 mx-1">
                    <button class="btn btn-primary btn-sm" type="submit">Filtrar</button>
                </div>

            </form>
        </div>
    </div>
    <hr class="p-0 m-0" />
    <div class="row px-3">
        <div class="col px-1">
            @foreach ($options as $option)
            @if( $filters && array_key_exists( $option['value'], $filters ) )
                @if( is_string( $filters[$option['value']] ) )
                    <span class="badge rounded-pill bg-primary mb-1">
                        {{ $option['label'] }}:"{{$filters[$option['value']]}}"
                        <a class="text-white" href="#" onclick="removeFilter('{{$option['value']}}', null)"><i class="bi bi-x-circle-fill"></i></a>
                    </span>
                @elseif( is_array( $filters[$option['value']]) )
                    @foreach ($filters[$option['value']] as $key => $filter)
                        <span class="badge rounded-pill bg-primary mb-1">
                            {{$option['label'] }}:"{{$filters[$option['value']][$key]}}"
                            <a class="text-white" href="#" onclick="removeFilter('{{$option['value']}}', null)"><i class="bi bi-x-circle-fill"></i></a>
                        </span>                    
                    @endforeach
                @endif
            @endif
            @endforeach        
        </div>
    </div>
</div>