<?php
header('Content-Type: application/json');

// Inclui as funções comuns
include 'funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['data'])) {
        $data = sanitize($_POST['data']);

        // Valida a data
        if (!validar_data($data)) {
            echo json_encode([
                'success' => false,
                'message' => 'Formato de data inválido.'
            ]);
            exit;
        }

        // Lê os agendamentos existentes
        $agendamentos = ler_agendamentos($arquivo_json);

        // Obtém os horários já agendados para a data selecionada
        $horarios_ocupados = isset($agendamentos[$data]) ? $agendamentos[$data] : [];

        // Calcula os horários disponíveis
        $disponiveis = array_diff($todos_horarios, $horarios_ocupados);

        // Reindexa o array
        $disponiveis = array_values($disponiveis);

        echo json_encode([
            'success' => true,
            'horarios' => $disponiveis
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data não fornecida.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido.'
    ]);
}
?>
