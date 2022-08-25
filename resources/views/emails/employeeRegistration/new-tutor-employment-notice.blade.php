@php
$article = $employeeGender->name === 'Masculino' ? 'o' : 'a';
@endphp
@component('mail::message')
Prezada Equipe da Coordenação de Tutoria,<br /><br />
Sirvo-me do momento para comunicar o cadastramento d{{ $article }} tutor{{ $employeeGender->name === 'Masculino' ? '' : 'a' }} abaixo mencionad{{ $article }} junto ao <b>Curso de {{ $courseName }}</b>.<br /><br />
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
