{{-- <style>
    h3 {
        background-color: rgb(180, 180, 180);
    }

    h4 {
        background-color: rgb(230, 230, 230);
    }

</style> --}}

<h3>== Vínculo ==</h3><br />
<h4>> Dados Pessoais</h4><br />
Nome: {{ $bond->employee->name }}
<br /><br />
{{-- UF: {{ $bond->employee->addressState->uf }}
<br /><br /> --}}
Cidade: {{ $bond->employee->address_city }}
<br /><br />
{{-- Código de Área: {{ $bond->employee->area_code }}
<br /><br />
Telefone: {{ $bond->employee->phone }}
<br /><br />
Celular: {{ $bond->employee->mobile }}
<br /><br /> --}}
Email: {{ $bond->employee->email }}
<br /><br />
<h4>> Dados do vínculo</h4><br />
Atribuição: {{ $bond->role->name }}
<br /><br />
Curso: {{ $bond->course->name }}
<br /><br />
Polo: {{ $bond->pole->name }}
<br /><br />
Criado em: {{ $bond->created_at }}
<br /><br />
Início: {{ $bond->begin }}
<br /><br />
Fim: {{ $bond->end }}
<br /><br />
Voluntário: {{ $bond->volunteer }}
<br /><br />
Encerrado em: {{ $bond->terminated_on }}
<br /><br />
Impedido: {{ $bond->impediment }}
<br /><br />
Revisado em: {{ $bond->uaba_checked_on }}
<br /><br />
