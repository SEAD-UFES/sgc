{{ $article = $receiverGender->name === 'Masculino' ? 'o' : 'a' }}
@component('mail::message')
    Prezad{{ $article }} {{ $receiverRoleName }} {{ $receiverName }},

    Sirvo-me do momento para informar que o seu login de acesso aos sistemas da Ufes foi criado e que, para a liberação do
    mesmo, basta criar sua senha de acesso, seguindo as instruções abaixo.

    Login: {{ $receiverInstitutionLogin }}

    Para Criar Senha:
    1. Acessar a página: https://senha.ufes.br/
    2. Clicar no menu 'Senha Única'
    3. Clicar na opção 'Recuperar senha Primeiro acesso'
    4. Leia as instruções apresentadas

    Caso haja, futuramente, a necessidade de Alterar a Senha:
    1. Acessar a página: https://senha.ufes.br/
    2. Clicar no menu 'Senha Única'
    3. Clicar na opção 'Alterar Senha'
    4. Leia as instruções apresentadas


    De posse do login único e senha, você terá acesso à Plataforma Moodle dos Cursos EAD, através do seguinte endereço:

    @component('mail::button', ['url' => $lmsUrl])
        {{ $lmsUrl }}
    @endcomponent

    Para uso do seu E-mail Institucional: {{ $receiverInstitutionEmail }}, basta acessar a página: https://mail.ufes.br.


    Atenciosamente,

    {{ $senderName ?? 'Secretaria Sead' }}
    Secretaria Acadêmica - Sead/Ufes
@endcomponent
