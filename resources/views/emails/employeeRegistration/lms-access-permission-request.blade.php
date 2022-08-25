@php
$article = $employeeGender->name === 'Masculino' ? 'o' : 'a';
@endphp
@component('mail::message')
Prezada Equipe DEDI,<br /><br />
A pedido da Coordenação do <b>Curso de {{ $courseName }}</b>, venho solicitar a liberação de acesso d{{ $article }} colaborador{{ $employeeGender->name === 'Masculino' ? '' : 'a' }} abaixo mencionad{{ $article }} às salas das disciplinas deste referido curso.<br /><br />
Nome: {{ $employeeName }}<br />
Função: {{ $employeeRoleName }}<br />
@if ($poleName !== 'SEAD')
Polo: {{ $poleName }}<br />
@endif
<br />
Login de acesso: {{ $employeeInstitutionLogin }}<br /><br />
E-mail Pessoal: {{ $employeePersonalEmail }}<br />
E-mail Institucional: {{ $employeeInstitutionEmail }}<br /><br />
Telefone: {{ isset($employeePhone) ? preg_replace('~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employeePhone) : '-' }}<br />
Celular: {{ isset($employeeMobile) ? preg_replace('~(\d{2})[^\d]{0,7}(\d{5})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employeeMobile) : '-' }}<br /><br /><br />
Atenciosamente,<br /><br />
{{ $senderName ?? 'Secretaria Sead' }}<br />
E-mail: {{ $senderInstitutionalEmail ?? 'secretaria.sead@ufes.br'}}<br />
Secretaria Acadêmica - Sead/Ufes
@endcomponent
