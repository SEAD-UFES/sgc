# dúvidas sobre documentos:

-   Checar arquivos inmportantes (scrum?)
-   Como enviar diagramas?

# sobre a estrutura do banco:

-   Como grant e grant_type será usado?
-   Approveds poderia se tornar "convocação" e dos dados de contato gravados em "employe"?
-   -   Deletar approveds?
-   "Bonds" sem "Pole" ou criar um "Pole" SEAD? (Cargos da SEAD.)
-   -   Tutores a distância: Ná teoria sem "Pole" na pratica com varios "Poles" como tratar?
-   "employe_documents" e "bond_documents" poderiam ser uma tabela só?
-   Já que não tem soft_delete, não seria melhor implementar o delete com "ON DELETE CASCATE"?
    -   Sem transações nesses deletes, se der qualquer problema, o banco pode ficar quebrado.
-   Modelos do tipo CourseType: No banco está ficando course_types, alguma politica pra isso?
-   Em courses datas (begin, end) são para permitir 2 cursos de mesmo nome, com identificadores de data diferentes?
-   "unique names" em modelos?

# dúvidas sobre código:

-   Fazer uso e "options" em funções para facilitar a leitura do código?
-   WebController: authcheck em um controlador protegido por auth middleware?
-   Proteger logout com auth?
-   por que "session(['sessionUser' => $sessionUser])" ?
-   try/catch devolvendo erros, muito bom. talvez voltar para mesma tela?
-   validações com validate.
-   Agregar detalhes no log (alterações efetuadas nos modelos) ?

# dúvidas sobre blade/interface:

-   O uso de "back": Será muito incomum mas, o que acontece se não tiver uma "back" para voltar?
-   Approveds: Como voltar o status para "contatado"?
-   Colocar cadastrar dentro de listar?
-   Ordenar listas com "created_at" e "desc"?

# dúvidas sobre diagramas:

-   Onde acessar o mwb?
-   Paineis para isolar subsistemas mwb
-   Alterando a cor das entidades mwb

# dúvidas sobre proximos passos:

-   como será o sistema de permissões?
