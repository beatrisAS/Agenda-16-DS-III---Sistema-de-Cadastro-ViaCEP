# 📦 Sistema de Gestão de Clientes - Integração ViaCEP (Enlatados Juarez)

Projeto desenvolvido como parte da Agenda 16 da disciplina de Desenvolvimento de Sistemas III.

Este projeto consiste em um **Sistema de Cadastro de Clientes** que utiliza integração com Web Services (API do ViaCEP) para preenchimento automático de endereços e validação de dados logísticos.

## 📺 Demonstração

O vídeo de apresentação do funcionamento do sistema está disponível no YouTube:

🔗 **[Clique aqui para assistir à demonstração do projeto](https://youtu.be/ci85Zj9tw1k)**

---

## 🚀 Funcionalidades

O sistema foi projetado para resolver problemas de erros de digitação em cadastros logísticos:

- **Busca Automática de CEP:** Consumo da API ViaCEP (formato XML) para preencher Logradouro, Bairro, Cidade e Estado.
- **Trava de Segurança (UX):** O campo "Número" permanece bloqueado até que um CEP válido seja consultado, garantindo a integridade do endereço.
- **Tratamento de Erros:** Validação de CEPs inexistentes ou com formato inválido, com feedback visual (Popups) para o usuário.
- **Persistência em Sessão:** Utilização de `$_SESSION` do PHP para simular um banco de dados e manter um histórico de cadastros recentes na tela.
- **Interface Responsiva:** Layout moderno desenvolvido com **Bootstrap 5**.

## 🛠️ Tecnologias Utilizadas

- **PHP 8+**: Processamento backend e consumo de Web Service (`simplexml_load_file`).
- **XML**: Formato de dados para integração.
- **HTML5 & CSS3**: Estrutura e estilização.
- **Bootstrap 5**: Framework CSS para responsividade e componentes (Modais, Grids).
- **Git & GitHub**: Versionamento de código.

## 📚 Contexto Acadêmico

Este projeto foi desenvolvido para avaliação na seguinte disciplina:

- **Curso:** Técnico em Desenvolvimento de Sistemas
- **Disciplina:** Desenvolvimento de Sistemas III
- **Agenda:** 16 - Web Services
- **Instituição:** ETEC (Centro Paula Souza)

## 📂 Como Executar o Projeto

1. **Pré-requisitos:** Tenha um servidor local instalado (XAMPP, WAMP ou Laragon) com PHP habilitado.
2. **Clone o repositório:**
   ```bash
   git clone [https://github.com/BeatrisAS/NOME-DO-SEU-REPOSITORIO.git](https://github.com/BeatrisAS/NOME-DO-SEU-REPOSITORIO.git)

3.  **Instalação:**
      - Mova os arquivos para a pasta pública do seu servidor (ex: `C:\xampp\htdocs\agenda16`).
4.  **Execução:**
      - Inicie o servidor Apache.
      - Acesse no navegador: `http://localhost/agenda16/VoceNoComando.php`.

-----

## 👩‍💻 Autora
Desenvolvido por **[@BeatrisAS](https://github.com/beatrisAS)**

-----

*Desenvolvido para fins educacionais.*

