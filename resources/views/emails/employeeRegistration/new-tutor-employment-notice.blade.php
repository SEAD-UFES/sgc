{{$article = $employeeGender->name === 'Masculino' ? 'o' : 'a'}}

Prezada Equipe da Coordenação de Tutoria

Sirvo-me do momento para comunicar o cadastramento d{{$article}} tutor{{$employeeGender->name === 'Masculino' ? '' : 'a'}} abaixo mencionad{{$article}} junto ao Curso {{$courseName}}.

Nome: {{$employeeName}}

Função: {{$employeeRoleName}}

Polo: {{$poleName}}

Login de acesso: {{$employeeInstitutionLogin}}

E-mail Pessoal: {{$employeePersonalEmail}}

E-mail Institucional: {{$employeeInstitutionEmail}}

Telefone: {{$employeePhone}}
Celular: {{$employeeMobile}}


Atenciosamente,

{{$senderName ?? 'Secretaria Sead'}}
Secretaria Acadêmica - Sead/Ufes

