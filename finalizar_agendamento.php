<?php
// Inclui as funções comuns
include 'funcoes.php';

// Mapeamento de tipos de corte para seus valores
$tipos_corte_valores = [
    'Barba' => 20,
    'Maquina' => 25,
    'Tesoura' => 30,
    'Disfarcado' => 20,
    'Sobrancelha' => 15
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe e sanitiza os dados do formulário
    $nome = isset($_POST['nome']) ? sanitize($_POST['nome']) : '';
    $telefone = isset($_POST['telefone']) ? sanitize($_POST['telefone']) : '';
    $tipo_corte = isset($_POST['tipo_corte']) ? sanitize($_POST['tipo_corte']) : '';
    $data = isset($_POST['data']) ? sanitize($_POST['data']) : '';
    $horario = isset($_POST['horario']) ? sanitize($_POST['horario']) : '';

    // Valida se todos os campos estão preenchidos
    if (empty($nome) || empty($telefone) || empty($tipo_corte) || empty($data) || empty($horario)) {
        echo "<h1>Erro</h1><p>Todos os campos são obrigatórios.</p>";
        exit;
    }

    // Valida a data
    if (!validar_data($data)) {
        echo "<h1>Erro</h1><p>Formato de data inválido.</p>";
        exit;
    }

    // Valida o horário
    if (!validar_horario($horario, $todos_horarios)) {
        echo "<h1>Erro</h1><p>Horário inválido.</p>";
        exit;
    }

    // Verifica se o tipo de corte é válido
    if (!array_key_exists($tipo_corte, $tipos_corte_valores)) {
        echo "<h1>Erro</h1><p>Tipo de corte inválido.</p>";
        exit;
    }

    // Obtém o valor do corte
    $valor_corte = $tipos_corte_valores[$tipo_corte];

    // Lê os agendamentos existentes
    $agendamentos = ler_agendamentos($arquivo_json);

    // Verifica se o horário já está ocupado
    if (isset($agendamentos[$data]) && in_array($horario, $agendamentos[$data])) {
        echo "<h1>Erro</h1><p>O horário selecionado já está ocupado. Por favor, escolha outro horário.</p>";
        exit;
    }

    // Adiciona o novo agendamento
    $agendamentos[$data][] = $horario;

    // Ordena os horários para manter a ordem
    sort($agendamentos[$data]);

    // Escreve os agendamentos atualizados no arquivo JSON
    escrever_agendamentos($arquivo_json, $agendamentos);

    // Exibe a confirmação do agendamento
    echo "<h1>Agendamento Confirmado</h1>";
    echo "<p><strong>Nome:</strong> $nome</p>";
    echo "<p><strong>Telefone:</strong> $telefone</p>";
    echo "<p><strong>Tipo de Corte:</strong> $tipo_corte - R$$valor_corte</p>";
    echo "<p><strong>Data:</strong> " . date("d/m/Y", strtotime($data)) . "</p>";
    echo "<p><strong>Horário:</strong> $horario</p>";
} else {
    echo "<h1>Método Inválido</h1>";
}
?>
