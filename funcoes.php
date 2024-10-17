<?php
// Caminho para o arquivo JSON
$arquivo_json = 'agendamentos.json';

// Horários disponíveis fixos
$todos_horarios = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00"];

// Função para ler o arquivo JSON
function ler_agendamentos($arquivo)
{
    if (!file_exists($arquivo)) {
        return [];
    }

    $conteudo = file_get_contents($arquivo);
    $dados = json_decode($conteudo, true);

    if (!is_array($dados)) {
        return [];
    }

    return $dados;
}

// Função para escrever no arquivo JSON com bloqueio
function escrever_agendamentos($arquivo, $dados)
{
    $fp = fopen($arquivo, 'w');
    if (flock($fp, LOCK_EX)) { // Bloqueia o arquivo para escrita
        fwrite($fp, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN); // Desbloqueia o arquivo
    }
    fclose($fp);
}

// Função para sanitizar entradas
function sanitize($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Função para validar a data no formato Y-m-d
function validar_data($data)
{
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $data);
}

// Função para validar o horário
function validar_horario($horario, $todos_horarios)
{
    return in_array($horario, $todos_horarios);
}
?>