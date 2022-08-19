
    Prezad{{ $receiverGender->name === 'Masculino' ? 'o' : 'a' }} {{ $receiverRoleName }} {{ $receiverName }},

    Sirvo-me do momento para informar que o seu login de acesso aos sistemas RedeUfes foi criado, e para liberação do mesmo,
    basta criar sua senha de acesso, seguindo as instruções abaixo:
    Login: {{ $receiverInstitutionLogin }}
    Acessar a pagina: senha.ufes.br (usar seu login de acesso)

    Para Criar\Alterar Senha:

    a) Clicar em Senha Única (no topo da página)

    b) Escolha a opção Alterar Senha.

    De posse do login único e senha, você terá acesso a Plataforma Moodle dos Cursos EAD, através da seguinte página
    eletrônica:

    - Portal da EAD\UFES (AVA Moodle): ead.ufes.br
    <a href="{{ $lmsUrl }}" data-bs-toggle="tooltip" title="Portal da EAD\UFES (AVA Moodle)" class="btn btn-primary btn-sm">
        Portal da EAD\UFES (AVA Moodle)
    </a>


    Para uso do seu E-mail Institucional: {{ $receiverInstitutionLogin }}@{{ $receiverRoleEmailDomain }},

    basta acessar a página: https://mail.ufes.br



    Thanks,<br>
    {{ $senderName ?? 'Secretaria Sead' }}
    {{ config('app.name') }}

