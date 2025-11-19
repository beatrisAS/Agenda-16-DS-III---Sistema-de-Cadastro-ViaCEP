<?php

if(!isset($_SESSION)) {
    session_start();
}


if (!isset($_SESSION['historico_clientes'])) {
    $_SESSION['historico_clientes'] = [];
}


if (isset($_GET['limpar_historico'])) {
    $_SESSION['historico_clientes'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$novoCadastroRealizado = false;
$erroEncontrado = false; 
$mensagemErro = "";
$ultimoCliente = [];


$camposFormulario = ['nome', 'cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'];
foreach ($camposFormulario as $campo) {
    if (!isset($_SESSION[$campo])) $_SESSION[$campo] = "";
}


function get_endereco($cep) {
    $cep = preg_replace("/[^0-9]/", "", $cep);
    $url = "http://viacep.com.br/ws/$cep/xml/";
    

    $xml = @simplexml_load_file($url);
    return $xml;
}


if(isset($_POST["btnBuscar"])) {
    $_SESSION["nome"] = $_POST["txtNome"];
    $_SESSION["cep"] = $_POST["txtCEP"];
    

    $_SESSION["numero"] = $_POST["txtNumero"] ?? ""; 
    
 
    $cepLimpo = preg_replace("/[^0-9]/", "", $_POST["txtCEP"]);
    
    if(strlen($cepLimpo) !== 8) {
       
        $erroEncontrado = true;
        $mensagemErro = "O CEP deve conter exatamente 8 números.";
        limparCamposEndereco();
    } else {
     
        $endereco = get_endereco($_POST["txtCEP"]);
        
        
        if(!$endereco || isset($endereco->erro)) {
            $erroEncontrado = true;
            $mensagemErro = "CEP não localizado na base de dados dos Correios.";
            limparCamposEndereco();
        } else {
          
            $_SESSION["logradouro"] = (string)$endereco->logradouro;
            $_SESSION["bairro"] = (string)$endereco->bairro;
            $_SESSION["cidade"] = (string)$endereco->localidade;
            $_SESSION["estado"] = (string)$endereco->uf;
            
            
            $_SESSION["numero"] = ""; 
        }
    }
}


if(isset($_POST["btnCadastrar"])) {
  
    if(empty($_SESSION["logradouro"])) {
        $erroEncontrado = true;
        $mensagemErro = "É necessário validar um CEP antes de salvar.";
    } else {
        $novoCliente = [
            'id' => count($_SESSION['historico_clientes']) + 1,
            'nome' => $_POST['txtNome'],
            'cep' => $_POST['txtCEP'],
            'endereco' => $_POST['txtLogradouro'] . ', ' . $_POST['txtNumero'],
            'local' => $_POST['txtBairro'] . ' - ' . $_POST['txtCidade'] . '/' . $_POST['txtEstado'],
            'data' => date('d/m/Y H:i')
        ];

        array_unshift($_SESSION['historico_clientes'], $novoCliente);
        $ultimoCliente = $novoCliente;
        $novoCadastroRealizado = true;

       
        foreach ($camposFormulario as $campo) { $_SESSION[$campo] = ""; }
    }
}


function limparCamposEndereco() {
    $_SESSION["logradouro"] = "";
    $_SESSION["bairro"] = "";
    $_SESSION["cidade"] = "";
    $_SESSION["estado"] = "";
}


$classeInputCep = $erroEncontrado ? "is-invalid" : "";
$travarNumero = empty($_SESSION["logradouro"]) ? 'disabled' : '';
$bgNumero = empty($_SESSION["logradouro"]) ? 'bg-secondary bg-opacity-10' : 'bg-white';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enlatados Juarez | Gestão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary: #0f172a; --accent: #3b82f6; --bg: #f1f5f9; }
        body { background-color: var(--bg); font-family: 'Inter', sans-serif; color: #334155; }
        .system-card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; background: white; }
        .sidebar { background: var(--primary); color: white; min-height: 100%; padding: 30px; }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
        
        
        .modal-error .modal-header { background-color: #dc3545; color: white; }
        .modal-error .icon-box { color: #dc3545; border: 3px solid #dc3545; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px auto; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            <div class="row system-card g-0">
                
                <div class="col-lg-3 sidebar d-none d-lg-flex flex-column justify-content-between">
                    <div>
                        <div class="mb-5"><i class="fas fa-cubes fa-2x mb-3 text-info"></i><h4 class="fw-bold">Enlatados Juarez</h4><p class="small opacity-75">Sistema de Gestão Logística</p></div>
                        <div class="alert alert-dark bg-opacity-25 border-0 small"><i class="fas fa-shield-alt me-1"></i> <strong>Segurança:</strong><br>Validação de endereço obrigatória via WebService.</div>
                    </div>
                    <div class="small opacity-50">v2.5 Stable</div>
                </div>

                <div class="col-lg-9 p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark m-0">Novo Registro</h4>
                        <span class="badge bg-light text-dark border">Total: <?php echo count($_SESSION['historico_clientes']); ?></span>
                    </div>

                    <form action="#" method="post" autocomplete="off">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">NOME</label>
                                <input type="text" class="form-control" name="txtNome" value="<?php echo $_SESSION["nome"]?>" placeholder="Nome do Cliente">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">CEP</label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?php echo $classeInputCep; ?>" name="txtCEP" value="<?php echo $_SESSION["cep"]?>" placeholder="00000000" maxlength="9">
                                    <button class="btn btn-primary" name="btnBuscar" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                                <?php if($erroEncontrado): ?>
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle"></i> Verifique o CEP informado.</div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted">LOGRADOURO</label>
                                <input type="text" class="form-control bg-light" name="txtLogradouro" readonly value="<?php echo $_SESSION["logradouro"]?>">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted">NÚMERO</label>
                                <input type="text" class="form-control <?php echo $bgNumero; ?>" name="txtNumero" value="<?php echo $_SESSION["numero"]?>" placeholder="Nº" <?php echo $travarNumero; ?>> 
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">BAIRRO</label>
                                <input type="text" class="form-control bg-light" name="txtBairro" readonly value="<?php echo $_SESSION["bairro"]?>">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-muted">CIDADE/UF</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light" name="txtCidade" readonly value="<?php echo $_SESSION["cidade"]?>">
                                    <span class="input-group-text bg-light">/</span>
                                    <input type="text" class="form-control bg-light" name="txtEstado" readonly style="max-width: 60px;" value="<?php echo $_SESSION["estado"]?>">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light">
                        <div class="d-flex justify-content-end">
                             <?php if(empty($_SESSION["logradouro"])): ?>
                                <button type="button" class="btn btn-secondary disabled" style="cursor: not-allowed;"><i class="fas fa-lock me-2"></i>Aguardando Validação</button>
                             <?php else: ?>
                                <button type="submit" name="btnCadastrar" class="btn btn-success px-4 fw-bold shadow-sm"><i class="fas fa-save me-2"></i>SALVAR REGISTRO</button>
                             <?php endif; ?>
                        </div>
                    </form>

                    <?php if(!empty($_SESSION['historico_clientes'])): ?>
                    <div class="mt-5 pt-4 border-top">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="fw-bold text-secondary">Últimos Cadastros</h6>
                            <a href="?limpar_historico=true" class="text-danger small text-decoration-none"><i class="fas fa-trash"></i> Limpar</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small">
                                <thead class="table-light"><tr><th>Cliente</th><th>Endereço</th><th>Local</th></tr></thead>
                                <tbody>
                                    <?php foreach ($_SESSION['historico_clientes'] as $c): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo $c['nome']; ?></td>
                                        <td><?php echo $c['endereco']; ?></td>
                                        <td><?php echo $c['local']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSucesso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 text-center p-4">
            <div class="mb-3 text-success"><i class="fas fa-check-circle fa-4x animate__animated animate__bounceIn"></i></div>
            <h5 class="fw-bold">Registrado!</h5>
            <p class="text-muted small mb-4">Cliente <strong><?php echo $ultimoCliente['nome'] ?? ''; ?></strong> salvo com sucesso.</p>
            <button type="button" class="btn btn-dark w-100 rounded-pill" data-bs-dismiss="modal">Continuar</button>
        </div>
    </div>
</div>

<div class="modal fade modal-error" id="modalErro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 text-center p-4">
            <div class="icon-box mb-3"><i class="fas fa-times fa-2x"></i></div>
            <h4 class="fw-bold text-danger">Falha na Busca</h4>
            <p class="text-muted my-3"><?php echo $mensagemErro; ?></p>
            <div class="alert alert-secondary bg-opacity-10 border-0 small text-start">
                <i class="fas fa-lightbulb text-warning me-1"></i> <strong>Dica:</strong> Verifique se o CEP possui 8 dígitos e se ele existe na base dos Correios.
            </div>
            <button type="button" class="btn btn-outline-danger w-100 rounded-pill mt-2" data-bs-dismiss="modal">Tentar Novamente</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
    <?php if($novoCadastroRealizado): ?>
        new bootstrap.Modal(document.getElementById('modalSucesso')).show();
    <?php endif; ?>

    <?php if($erroEncontrado): ?>
        new bootstrap.Modal(document.getElementById('modalErro')).show();
    <?php endif; ?>
</script>
</body>
</html>