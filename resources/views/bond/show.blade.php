@extends('layouts.basic')

@section('title', 'Exibir Vínculo')

@section('content')
    <section>
        <h2>Vínculo</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @component('bond.componentBondDetails', compact('bond'))@endcomponent
            <h4>> Documentos</h4><br />
            @component('bond.document.componentList', compact('documents'))@endcomponent
            <a href="{{ route('bonds.document.massdownload', $bond) }}"
                style="text-decoration: none; font-weight:bold;">&nbsp;&#8627; Fazer o download de todos os documentos do vínculo (zip)</a>
            <br /><br /><br />
            <fieldset class="bg-warning px-2 py-2">
            <h4>> Revisão</h4>
            <form name="{{ 'formReview' . $bond->id }}" action="{{ route('bonds.review', $bond) }}" method="POST">
                @csrf
                    <label class="form-label">Impedido</label><br />
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="radio" id="sim" name="impediment" value="1"
                            {{ $bond->impediment == '1' ? 'checked' : '' }}
                            onclick="document.getElementById('impedimentDescription').disabled=false;">
                        <label for="sim" class="form-check-label">Sim</label><br>
                        <input class="form-check-input" type="radio" id="nao" name="impediment" value="0"
                            {{ $bond->impediment == '0' ? 'checked' : '' }}
                            onclick="document.getElementById('impedimentDescription').disabled=true;">
                        <label for="nao" class="form-check-label">Não</label><br>
                    </div>
                    <div class="mb-3">
                        <label for="impedimentDescription" class="form-label">Motivo do impedimento:</label><br />
                        <textarea class="form-control" id="impedimentDescription" name="impedimentDescription"
                            rows="4">{{ $bond->impediment_description ?? '' }}</textarea>
                        <script>
                            if ({{ $bond->impediment }} == '0')
                                document.getElementById('impedimentDescription').disabled = true;
                        </script>
                    </div>
                    <input type="submit" value="Revisar" class="btn btn-primary my-2 mx-1">
                </form>
            </fieldset><br />
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
        </main>
    </section>
@endsection
