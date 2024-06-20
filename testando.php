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
    <title>Mapa Google - Itapira</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <link href="style.css" rel="stylesheet">
    <style>
        #map {
            height: 600px;
            width: 70%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
		
		
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .ativo {
            background-color: #d4edda;
        }
        .inativo {
            background-color: #f8d7da;
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
<body style="margin: 30px 40px 40px 50px;">
    <div class="row">
        <div class="col-sm-5 col-md-6">
            <h1>Tabela de Status</h1>
            <table>
                <thead>
                    <tr>
                        <th>Núm. Bueiro</th>
                        <th>Bairro</th>
						<th>Capacidade</th>
                        <th>Última Medição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dados)): ?>
					
					<?php
						$valoresIniciais = []; // Array de valores iniciais
						$numeroBueiro = [];
						$bairro = [];
						$rua = [];
						//$latitude = [];
						//$longitude = [];
						$colorIcon = [];
					?>
					
                          <?php foreach ($dados as $linha): ?>
                            <?php
							
							    $cor = "";
                                if ($linha['nivel_atual_bueiro'] /$linha['profundidade'] == 0) {
                                    $cor = "background-color: green;";
									array_push($colorIcon, "greenIcon");
                                } elseif ($linha['nivel_atual_bueiro'] /$linha['profundidade'] <= 0.5) {
                                    $cor = "background-color: yellow;";
									array_push($colorIcon, "yellowIcon");
                                } elseif ($linha['nivel_atual_bueiro'] /$linha['profundidade'] > 0.5) {
                                    $cor = "background-color: red;";
									array_push($colorIcon, "greenIcon");
                                } else {
                                    $cor = "background-color: blue;";
                                }
								
								$valor = 0;
								if($linha['nivel_atual_bueiro'] != 0){
									$valor = $linha['nivel_atual_bueiro'] /$linha['profundidade'];
								}
								
								//echo ($valor * 100) . " ";
							
							array_push($valoresIniciais, $valor * 100);
							array_push($numeroBueiro, $linha['numero_bueiro']);
							array_push($bairro, $linha['bairro']);
							array_push($rua, $linha['rua']);
							//array_push($latitude, $linha['latitude']);
							//array_push($longitude, $linha['longitude']);
						              
								
                            ?>
                            <tr style="<?php echo $cor; ?>">
                                <td><?php echo htmlspecialchars($linha['numero_bueiro']); ?></td>
                                <td><?php echo htmlspecialchars($linha['bairro']); ?></td>
								 <td><?php echo htmlspecialchars($linha['profundidade']); ?></td>
                                <td><?php echo htmlspecialchars($linha['nivel_atual_bueiro']); ?></td>
                            </tr>
                        <?php endforeach; ?>
						<?php rsort($valoresIniciais); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Nenhum dado encontrado.</td>
                        </tr>
                    <?php endif; ?>
			
                </tbody>
            </table>
        </div>
	
	  <div id="velocimetro">
        <div class="gauge"></div>
    </div>
    <form action="update_speed.php" method="post">
        
    </form>
<div class="container">
<div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0">
<h1>Mapa de Itapira</h1>
<div id="map"></div>
<script src="https://maps.googleapis.com/maps/api/js?keyAIzaSyBIwC6k3B6wTaDbotoq598BPFSq0YkxOAgKEY&callback=initMap" async defer></script>
    <script>
                function initMap() {
                    var itapira = { lat: -22.4358, lng: -46.8227 };
                    var options = {
                        zoom: 12,
                        center: itapira
                    }
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
					
					  <?php foreach ($dados as $linha): ?>
					  
					  <?php
					  
					   $iconeCor = "redIcon";
                                if ($linha['nivel_atual_bueiro'] < 0.5 ) {
                                    $iconeCor = "greenIcon";
                                } elseif ($linha['nivel_atual_bueiro'] /$linha['profundidade'] <= 0.5) {
                                    $iconeCor = "yellowIcon";
                                } elseif ($linha['nivel_atual_bueiro'] /$linha['profundidade'] > 0.5) {
                                    $iconeCor = "redIcon";
                                } else {
                                    $iconeCor = "blackIcon";
                                }
					  
					  
					  ?>
					  
                   new google.maps.Marker({
                        position: { lat: <?php echo $linha['latitude']; ?>, lng: <?php echo $linha['longitude']; ?> },
                        map: map,
                        title: 'Bueiro <?php echo $linha['numero_bueiro']; ?>',
                        icon: <?php echo $iconeCor ;?>
                     }).addListener('click', function() {
						handleMarkerClick(this, 'velocimetro<?php echo ($linha['numero_bueiro'] - 1); ?>', 'dados<?php echo ($linha['numero_bueiro'] - 1); ?>');
						});
					<?php endforeach; ?>
					
					//marker.addListener('click', function() {
                   // handleMarkerClick(marker);
                   //  });
                  /*  new google.maps.Marker({
                        position: { lat: -22.4400, lng: -46.8250 },
                        map: map,
                        title: 'Amarelo',
                        icon: yellowIcon
                    });
                    new google.maps.Marker({
                        position: { lat: -22.4300, lng: -46.8200 },
                        map: map,
                        title: 'Verde',
                        icon: greenIcon
                    });
                    new google.maps.Marker({
                        position: { lat: -22.4200, lng: -46.8200 },
                        map: map,
                        title: 'Preto',
                        icon: blackIcon
                    });*/
                }
				
		function handleMarkerClick(marker, id, idDados) {
           // alert("Você clicou no marcador com título: " + marker.getTitle());
		   toggleDivDadosVisibility(idDados);
            toggleDivVisibility(id);
			
            // Ações adicionais podem ser realizadas aqui
        }
</script>
</div>
	
	
</body>



<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['gauge']});
        google.charts.setOnLoadCallback(drawCharts);
		
        function drawCharts() {
            <?php foreach ($valoresIniciais as $index => $valorInicial): ?>
            drawChart(<?php echo $valorInicial; ?>, 'velocimetro<?php echo $index; ?>');
            <?php endforeach; ?>
        }

        function drawChart(valorInicial, elementId) {
		//	alert(valorInicial);
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['%', valorInicial]
            ]);

            var options = {
                width: 400,
                height: 220,
                redFrom: 60, redTo: 100,
                yellowFrom: 40, yellowTo: 60,
                greenFrom: 0, greenTo: 40,
                minorTicks: 10,
                max: 100,
                majorTicks: ['0%', '10%', '20%', '30%', '40%', '50%', '60%', '70%', '80%', '90%', '100%'],
                animation: { duration: 400, easing: 'inAndOut' }
            };

            var chart = new google.visualization.Gauge(document.getElementById(elementId));

            chart.draw(data, options);

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
		
		  function toggleDivVisibility(id) {
			var divs = document.getElementsByClassName('velocimetro');
			for (var i = 0; i < divs.length; i++) {
				//if (divs[i].id !== id) {
					divs[i].style.display = 'none';
				//}
			}

			var div = document.getElementById(id);
			if (div.style.display === 'none' || div.style.display === '') {
				div.style.display = 'block';
				if (!chartsDrawn) {
					google.charts.setOnLoadCallback(drawCharts);
					chartsDrawn = true;
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
    
	    <style>
        #oculta {
            display: none; /* Inicialmente oculta */
        }
    </style>
	
	
    <?php 
        foreach ($valoresIniciais as $index => $valorInicial): ?>
			
			<div class="velocimetro" id="velocimetro<?php echo $index; ?>" style="display: none; margin: 20px;">
                <!-- Velocímetro será desenhado aqui -->	
            </div>
			<div class="dados" id="dados<?php echo $index; ?>" style="display: none; margin: 20px;">
            <h6>Bueiro: <?php echo $numeroBueiro[$index]; ?></h6>
			<h6>Bairro: <?php echo $bairro[$index]; ?></h6>
            <h6>Rua: <?php echo $rua[$index]; ?></h6>
			</div>
            
        <?php endforeach; ?>
    
</html>
