<script type="text/javascript">
    function buildAllFilters(){
        return {!! json_encode($filters, JSON_HEX_TAG) !!};
    }

    function build_query_string(options={}){
            //if object
            if(typeof options.filters === 'object'){
                let keys = Object.keys(options.filters);
                let qs_fragments = keys.map( key => {
                    let new_filters = options.filters[key];
                    let new_key = options.key ? options.key + '[' + key + ']' : key
                    let qs_fragment = build_query_string({ filters: new_filters, key: new_key});
                    return qs_fragment;
                });
                let qs_object_fragment = qs_fragments.reduce( (acc='', curr) => (acc + curr), '' )
                return qs_object_fragment;
            }

            //if string
            if(typeof options.filters === 'string'){
                let qs_string_fragment = '&' + options.key + '=' + options.filters;
                return qs_string_fragment;
            }
    }

    function buildURL(filters={}){
        let address = window.location.origin+window.location.pathname+'?';
        let qs = build_query_string({filters: filters, key: ''});
        return address + qs;
    }

    function submitFilters(event){
        event.preventDefault();

        //buildfilters
        let filters = buildAllFilters()

        //
        let new_type = document.querySelector('#filterType').value;
        let new_value = document.querySelector('#filterValue').value;
        let filter_key = Object.keys(filters).find(f_key => f_key === new_type);
        
        //if filter[key] dont exists
        if (!filter_key) {
            filters[new_type] = [];
            filters[new_type].push(new_value);
        }

        // if filter[key] is object
        else if (typeof filters[filter_key] === 'object') {
            filters[filter_key].push(new_value);
        }
        
        // if filter[key] is string
        else if (typeof filters[filter_key] === 'string') {
            filters[filter_key] = [ filters[filter_key] ];
            filters[filter_key].push(new_value);
        }

        // build url and redirect
        let address = buildURL(filters);
        window.location.href = address;
    }

    
    function removeFilter(ft_key, idx){

        //building filters object (on javascript)
        let filters = buildAllFilters()

        //
        let filter_key = Object.keys(filters).find(f_key => f_key === ft_key);

        // if filter[key] is object
        if (typeof filters[filter_key] === 'object') {
            filters[filter_key].splice(idx, 1);
        }

        // if filter[key] is string
        else if (filter_key && typeof filters[filter_key] === 'string') {            
            delete filters[filter_key];
        }

        //build url and redirect
        let address = buildURL(filters);
        window.location.href = address;
    }

</script>