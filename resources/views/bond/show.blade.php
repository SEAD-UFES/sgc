@extends('layouts.basic')

@section('title', 'Exibir Vínculo')

@section('content')
    <section>
        <strong>Exibir Vínculo</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @component('bond.componentBondDetails', compact('bond'))@endcomponent
            <h4>> Documentos</h4><br />
            @component('bond.document.componentList', compact('documents'))@endcomponent
            <br />
            <h4>> Revisão</h4><br />
            <form name="{{ 'formReview' . $bond->id }}" action="{{ route('bonds.review', $bond) }}" method="POST">
                @csrf
                <fieldset style="background-color:lightsalmon; padding:5px">
                    <p>Impedido:</p>
                    <input type="radio" id="sim" name="impediment" value="1"
                        {{ $bond->impediment == '1' ? 'checked' : '' }}
                        onclick="document.getElementById('impedimentDescription').disabled=false;">
                    <label for="sim">Sim</label><br>
                    <input type="radio" id="nao" name="impediment" value="0"
                        {{ $bond->impediment == '0' ? 'checked' : '' }}
                        onclick="document.getElementById('impedimentDescription').disabled=true;">
                    <label for="nao">Não</label><br>
                    <br />
                    <label for="impedimentDescription">Motivo do impedimento:</label><br />
                    <textarea id="impedimentDescription" name="impedimentDescription" rows="4" cols="50">{{ $bond->impediment_description ?? '' }}</textarea>
                    <script>
                        if ({{ $bond->impediment }} == '0')
                            document.getElementById('impedimentDescription').disabled = true;
                    </script>
                    <br />
                    <input type="submit" value="Revisar">
                </fieldset>
            </form>
            <br /><br /><br />
            <button type="button" onclick="history.back()">Voltar</button>
            <br /><br /><br />
        </main>
    </section>
@endsection
