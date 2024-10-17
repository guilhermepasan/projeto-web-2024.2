// script.js

document.getElementById('data').addEventListener('change', function() {
    const dataSelecionada = this.value;
    const horariosSelect = document.getElementById('horario');
    const horariosDiv = document.getElementById('horarios_disponiveis');
    const loading = document.getElementById('loading');
    const dataFormatadaP = document.getElementById('data_formatada'); // Novo elemento

    // Limpa os horários anteriores
    horariosSelect.innerHTML = '<option value="">Selecione um horário</option>';
    horariosDiv.style.display = 'none';
    dataFormatadaP.textContent = ''; // Limpa a data formatada

    if (dataSelecionada) {
        loading.style.display = 'block';

        // Inutil pq o navegador converte sozinho
        // Converte a data para d/m/y
        //const partesData = dataSelecionada.split('-'); // ['YYYY', 'MM', 'DD']
        //const dataFormatada = `${partesData[2]}/${partesData[1]}/${partesData[0]}`;
        //dataFormatadaP.textContent = `Data Selecionada: ${dataFormatada}`; // Exibe a data formatada

        // Faz uma requisição AJAX para obter os horários disponíveis
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'buscar_horarios.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                loading.style.display = 'none';

                if (response.success) {
                    const horarios = response.horarios;
                    if (horarios.length > 0) {
                        horarios.forEach(function(horario) {
                            const option = document.createElement('option');
                            option.value = horario;
                            option.textContent = horario;
                            horariosSelect.appendChild(option);
                        });
                        horariosDiv.style.display = 'block';
                    } else {
                        alert('Nenhum horário disponível para a data selecionada.');
                    }
                } else {
                    alert('Erro ao buscar horários disponíveis: ' + response.message);
                }
            }
        };

        xhr.send('data=' + encodeURIComponent(dataSelecionada));
    }
});
