@php
$article = $employeeGender->name === 'Masculino' ? 'o' : 'a';
@endphp
@component('mail::message')
Prezada Equipe DEDI,

A pedido da Coordenação do **Curso de {{ $courseName }}**, venho solicitar a liberação de acesso d{{ $article }} colaborador{{ $employeeGender->name === 'Masculino' ? '' : 'a' }} abaixo mencionad{{ $article }} às salas das disciplinas deste referido curso.

Nome: {{ $employeeName }}  
Função: {{ $employeeRoleName }}
@if ($poleName !== 'SEAD')
<br />Polo: {{ $poleName }}
@endif

Login de acesso: {{ $employeeInstitutionLogin }}

E-mail Pessoal: <{{ $employeePersonalEmail }}>  
E-mail Institucional: <{{ $employeeInstitutionEmail }}>

Telefone: {{ isset($employeePhone) ? '[' . preg_replace('~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employeePhone) . '](tel:' . $employeePhone . ')' : '-' }}  
Celular: {{ isset($employeeMobile) ? '[' . preg_replace('~(\d{2})[^\d]{0,7}(\d{5})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employeeMobile) . '](tel:' . $employeeMobile . ')' : '-' }}

&nbsp;

Atenciosamente,

{{ $senderName ?? 'Secretaria Sead' }}  
E-mail: <{{ $senderInstitutionalEmail ?? 'secretaria.sead@ufes.br'}}>  
Secretaria Acadêmica - Sead/Ufes
@endcomponent
