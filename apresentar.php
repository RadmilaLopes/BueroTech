<?php
// Função para carregar dados do banco de dados
function carregar() {
    try {
        // Configurações do banco de dados
        $dsn = 'mysql:host=127.0.0.1;dbname=banco-bueiro;charset=utf8';
        $username = 'root';
        $password = '';

        // Criar conexão usando PDO
        $conexao = new PDO($dsn, $username, $password);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL
        $sql = "SELECT a.numero_bueiro, a.bairro, b.nivel_bueiro nivel_atual_bueiro,
        a.profundidade, a.rua, a.latitude, a.longitude
                FROM (
                    SELECT m.id_bueiro, b.numero_bueiro, b.bairro, b.rua, MAX(m.date_time) data,
                    b.profundidade, b.latitude, b.longitude
                    FROM tb_medicao m
                    INNER JOIN tb_bueiro b ON b.id = m.id_bueiro
                    GROUP BY m.id_bueiro, b.numero_bueiro, b.bairro, b.rua, b.profundidade,
                    b.latitude, b.longitude
                ) a
                INNER JOIN (
                    SELECT id_bueiro, date_time, nivel_bueiro 
                    FROM tb_medicao
                ) b ON a.id_bueiro = b.id_bueiro AND a.data = b.date_time
                order by b.nivel_bueiro / a.profundidade desc";

        // Executar consulta
        $resultado = $conexao->query($sql);

        // Retornar resultados
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return [];
    }
}

// Carregar dados do banco de dados
$dados = carregar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status - Itapira</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
        .ativo {
            background-color: #d4edda;
        }
        .inativo {
            background-color: #f8d7da;
        }
        .titulo-direita {
            text-align: right;
            font-size: 24px;
        }
		.botao-atualizar {
            position: fixed;
            bottom: 30px;
            right: 180px; /* Alinha o botão à direita */
            width: 380px; /* Largura fixa para o botão */
            margin-top: 2.5rem;
            border: none;
            background-color: #2cccc4;
            padding: 0.62rem;
            border-radius: 5px;
            cursor: pointer;
		}
        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                border: 1px solid #ccc;
                margin-bottom: 10px;
            }
            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            td:before {
                position: absolute;
                top: 6px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }
            td:nth-of-type(1):before { content: "Núm. Bueiro"; }
            td:nth-of-type(2):before { content: "Bairro"; }
            td:nth-of-type(3):before { content: "Status"; }
        }
    </style>
</head>
<body style="width: 100%; height: 100vh; display: flex; justify-content: center; align-items: center; background: #f2f2f2;">
    <div class="container">
        <h1 class="titulo-centralizado" style="text-align: center;">Status</h1>
        <div class="row">
            <div class="col-md-6">
                <div id="map"></div>
            </div>
            <div class="col-md-6">
                <div class="velocimetro-container" style="display: grid; place-items: center; height: 80vh; width: 40vw;">
                    <?php foreach ($dados as $index => $linha): ?>
                    <div class="velocimetro" id="velocimetro<?php echo $index; ?>" style="display: none; margin: 20px;">
                        <!-- Velocímetro será desenhado aqui -->
                    </div>
                    <div class="dados" id="dados<?php echo $index; ?>" style="display: none; margin: 20px;">
                        <table>
                        <tr>
                        <td style="font-size: 22px; font-weight: bold;">Bueiro: </td>
                        <td style="font-size: 22px; font-weight: bold;"> <?php echo htmlspecialchars($linha['numero_bueiro']); ?> </td>
                        </tr>
                        <tr>
                        <td style="font-size: 22px; font-weight: bold;">Bairro: </td>
                        <td style="font-size: 22px; font-weight: bold;"> <?php echo htmlspecialchars($linha['bairro']); ?> </td>
                        </tr>
                        <tr>
                        <td style="font-size: 22px; font-weight: bold;">Rua: </td>
                        <td style="font-size: 22px; font-weight: bold;"> <?php echo htmlspecialchars($linha['rua']); ?> </td>
                        </tr>
                        </table>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
	
	
    
    <button class="botao-atualizar" onclick="location.reload();">Atualizar</button>
	
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIwC6k3B6wTaDbotoq598BPFSq0YkxOAg&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var itapira = { lat: -22.4358, lng: -46.8227 };
            var options = {
                zoom: 12,
                center: itapira
            };
            var map = new google.maps.Map(document.getElementById('map'), options);
            var redIcon = {
                url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
            };
            var yellowIcon = {
                url: 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
            };
            var greenIcon = {
                url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
            };
            var blackIcon = {
                url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            };
            <?php foreach ($dados as $index => $linha): ?>
            <?php
                $iconeCor = "redIcon";
                if ($linha['nivel_atual_bueiro'] < 0.5 ) {
                    $iconeCor = "greenIcon";
                } elseif ($linha['nivel_atual_bueiro'] / $linha['profundidade'] <= 0.5) {
                    $iconeCor = "yellowIcon";
                } elseif ($linha['nivel_atual_bueiro'] / $linha['profundidade'] > 0.5) {
                    $iconeCor = "redIcon";
                } else {
                    $iconeCor = "blackIcon";
                }
            ?>
            new google.maps.Marker({
                position: { lat: <?php echo $linha['latitude']; ?>, lng: <?php echo $linha['longitude']; ?> },
                map: map,
                title: 'Bueiro <?php echo htmlspecialchars($linha['numero_bueiro']); ?>',
                icon: <?php echo $iconeCor; ?>
            }).addListener('click', function() {
                handleMarkerClick(this, 'velocimetro<?php echo $index; ?>', 'dados<?php echo $index; ?>');
            });
            <?php endforeach; ?>
        }

        function handleMarkerClick(marker, id, idDados) {
            toggleDivDadosVisibility(idDados);
            toggleDivVisibility(id);
        }

        function toggleDivVisibility(id) {
            var divs = document.getElementsByClassName('velocimetro');
            for (var i = 0; i < divs.length; i++) {
                divs[i].style.display = 'none';
            }
            var div = document.getElementById(id);
            if (div.style.display === 'none' || div.style.display === '') {
                div.style.display = 'block';
                if (!window.chartsDrawn) {
                    google.charts.setOnLoadCallback(drawCharts);
                    window.chartsDrawn = true;
                }
            } else {
                div.style.display = 'none';
            }
        }

        function toggleDivDadosVisibility(id) {
            var divs = document.getElementsByClassName('dados');
            for (var i = 0; i < divs.length; i++) {
                divs[i].style.display = 'none';
            }
            var div = document.getElementById(id);
            if (div) {
                div.style.display = 'block';
            }
        }
    </script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['gauge']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            <?php foreach ($dados as $index => $linha): ?>
            drawChart(<?php echo $linha['nivel_atual_bueiro'] / $linha['profundidade'] * 100; ?>, 'velocimetro<?php echo $index; ?>');
            <?php endforeach; ?>
        }

        function drawChart(value, elementId) {
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['%', value]
            ]);

            var options = {
                width: 450,
                height: 350,
                redFrom: 60, redTo: 100,
                yellowFrom: 20, yellowTo: 60,
                greenFrom: 0, greenTo: 20,
                minorTicks: 10,
                max: 100,
                majorTicks: ['0%', '10%', '20%', '30%', '40%', '50%', '60%', '70%', '80%', '90%', '100%'],
                animation: { duration: 400, easing: 'inAndOut' }
            };

            var chart = new google.visualization.Gauge(document.getElementById(elementId));

            chart.draw(data, options);

            // Estilizar os majorTicks após a renderização do gráfico
            var labels = document.querySelectorAll(`#${elementId} text`);
            labels.forEach(function(label) {
                if (options.majorTicks.includes(label.textContent)) {
                    label.style.fontSize = '14px'; // Define o tamanho da fonte desejado
                    label.style.fontFamily = 'Arial, sans-serif'; // Define a família da fonte desejada
                }
            });

            function atualizarVelocidade(velocidade) {
                data.setValue(0, 1, velocidade);
                chart.draw(data, options);
            }

            setInterval(() => {
                fetch('get_speed.php')
                    .then(response => response.json())
                    .then(data => {
                        atualizarVelocidade(data.velocidade);
                    });
            }, 5000);
        }
    </script>
</body>
</html>
