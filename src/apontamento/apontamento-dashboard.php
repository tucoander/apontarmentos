<?php
    include('../template/template-barra.php');
    $db = new SQLite3('../sqlite/apontamentos.db');

    $labels = array();
    $data = array();
    $data_perc = array();

    $labels_script;
    $data_script;

    $soma_hora = 0;
    $tamanho;

    $table = '';
    $msg = '';
    $final = '';

    if(isset($_POST['filtro']) && isset($_POST['fr_adddte']) && isset($_POST['to_adddte'])){
        if(!empty($_POST['fr_adddte']) && !empty($_POST['to_adddte'])){

			if(!empty($_POST['usr_id'])){
				$s_tbllog = "
                SELECT 
                uo.opr_id,
                uo.oprnme,
                sum((julianday(to_logtim) - julianday(fr_logtim))*24) as diff_jd
                FROM usrlog ul
                    inner join  usrprd up 
                    on (ul.prd_id = up.prd_id)
                    inner join usrcty uc
                    on (ul.cty_id = uc.cty_id)
                    inner join usropr uo
                    on (ul.opr_id = uo.opr_id)
                where ul.logdte between :fr_adddte and :to_adddte
				and ul.usr_id = :usr_id
                group by
                uo.opr_id,
                    uo.oprnme
                order by 
                    ul.opr_id asc
                ";
                $final = ' para o usuário: '.$_POST['usr_id'];
				$cmd_db = $db->prepare($s_tbllog);
				$cmd_db->bindValue('usr_id', $_POST['usr_id']);
			}else{
				$s_tbllog = "
                SELECT 
                uo.opr_id,
                uo.oprnme,
                sum((julianday(to_logtim) - julianday(fr_logtim))*24) as diff_jd
                FROM usrlog ul
                    inner join  usrprd up 
                    on (ul.prd_id = up.prd_id)
                    inner join usrcty uc
                    on (ul.cty_id = uc.cty_id)
                    inner join usropr uo
                    on (ul.opr_id = uo.opr_id)
                where ul.logdte between :fr_adddte and :to_adddte
                group by
                uo.opr_id,
                    uo.oprnme
                order by 
                    ul.opr_id asc
                ";
                $final = '';
                $cmd_db = $db->prepare($s_tbllog);
			}
            

            $_usr_id = $_SESSION['usr_id'];
            $_fr_adddte = $_POST['fr_adddte'];
            $_to_adddte = $_POST['to_adddte'];

            
            $cmd_db->bindValue('fr_adddte', $_fr_adddte);
            $cmd_db->bindValue('to_adddte', $_to_adddte);
        
            $resultado = $cmd_db->execute();
           
            while($row = $resultado->fetchArray(SQLITE3_ASSOC)){
                $labels[] = $row['oprnme'];
                $data[] = $row['diff_jd'];
            }

            $msg = 'Intervalo Pesquisado: ';
            $msg .= date("d/m/Y",strtotime($_POST['fr_adddte']));
            $msg .= ' até ';
            $msg .= date("d/m/Y",strtotime($_POST['to_adddte']));
            $msg .= $final;
            $tamanho = 'style="height: 55em;"';
        }
        else {
           $msg = 'É necessário inserir data inicial e data final';
           $tamanho = 'style="height: 55em;"';
        }
    }
    else if(isset($_POST['todos'])){
        $s_tbllog = "
        SELECT 
        uo.opr_id,
        uo.oprnme,
        sum((julianday(to_logtim) - julianday(fr_logtim))*24) as diff_jd
        FROM usrlog ul
            inner join  usrprd up 
            on (ul.prd_id = up.prd_id)
            inner join usrcty uc
            on (ul.cty_id = uc.cty_id)
            inner join usropr uo
            on (ul.opr_id = uo.opr_id)
        group by
        uo.opr_id,
            uo.oprnme
        order by 
            ul.opr_id asc
        ";
        $_usr_id = $_SESSION['usr_id'];
        
        $cmd_db = $db->prepare($s_tbllog);
        
        $resultado = $cmd_db->execute();

        while($row = $resultado->fetchArray(SQLITE3_ASSOC)){
            $labels[] = $row['oprnme'];
            $data[] = $row['diff_jd'];
        }
        $tamanho = 'style="height: 50em;"';
    }
    else{
        $s_tbllog = "
        SELECT 
        uo.opr_id,
        uo.oprnme,
        sum((julianday(to_logtim) - julianday(fr_logtim))*24) as diff_jd
        FROM usrlog ul
            inner join  usrprd up 
            on (ul.prd_id = up.prd_id)
            inner join usrcty uc
            on (ul.cty_id = uc.cty_id)
            inner join usropr uo
            on (ul.opr_id = uo.opr_id)
        group by
            uo.opr_id,
            uo.oprnme
        order by 
            ul.opr_id asc
        ";
        $_usr_id = $_SESSION['usr_id'];
        
        $cmd_db = $db->prepare($s_tbllog);

        $resultado = $cmd_db->execute();

        while($row = $resultado->fetchArray(SQLITE3_ASSOC)){
            $labels[] = $row['oprnme'];
            $data[] = $row['diff_jd'];
        }
        $tamanho = 'style="height: 50em;"';
    }
    
    foreach($data as $rowsoma){
        $soma_hora = $soma_hora + $rowsoma;
    }

    foreach($data as $row){
        $data_perc[] = round($row / $soma_hora,4) * 100;
    }

    $table = '
    <div class="card" >
        <div class="card-body">
            <table class="table" id="apontamento">
                <thead>
                    <tr>
                    <th scope="col">Operação</th>
                    <th scope="col">Percentual</th>
                    <th scope="col">Horas</th>
                </thead>
                <tbody>
    ';

    foreach($data as $key=>$value){
        $table .= '
                    <tr>
                        <td scope="row">'.$labels[$key].'</td>
                        <td>'.$data_perc[$key].' %</td>
                        <td>'.floor($value).'h'.floor(($value-floor($value))*60).'m</td>
                    <tr>
        ';

    }
    $table .= '
                </tbody>
        </div>
    </div>
    ';

    $data_perc_script = implode("', '",$data_perc);
	$labels_script = implode("', '",$labels);
    $data_script = implode("', '",$data);
	
?>

<div role="main" class="container-fluid">
    <div class="card" >
        <div class="card-body" <?php print $tamanho;?>>
            <form method="POST" action="apontamento-dashboard.php">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control-plaintext" value="Data Início:">
                    </div>
                    <div class="col">
                        <input type="date" class="form-control" placeholder="Data" id="fr_adddte" name="fr_adddte">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control-plaintext" value="Data Final:">
                    </div>
                    <div class="col">
                        <input type="date" class="form-control" placeholder="Data" id="to_adddte" name="to_adddte">
                    </div>
                    <div class="col">
                        <input type="submit" class="btn btn-primary col-12" name="filtro" value="Filtrar">
                    </div>
                    <div class="col">
                        <input type="submit" class="btn btn-primary col-12" name="todos" value="Limpar Filtro" >
                    </div>
                </div>
                <hr>
                <div class="row">
                   
                        <div class="col-2">
                            <input type="text" class="form-control-plaintext" value="Usuário:">
                        </div>
                        <div class="col-6">
                        <select class="custom-select d-block w-100" id="usr_id" name="usr_id" onchange="">
                            <option value="">Selecione...</option>
                            <?php
                                $db = new SQLite3('../sqlite/apontamentos.db');
                                $s_tblprd = "
                                    SELECT 
                                    us.usr_id,
                                    us.usrnme
                                        FROM usrsys us
                                        GROUP BY 
                                        us.usr_id,
                                        us.usrnme
                                    ";
                                $resultado = $db->query($s_tblprd);
                                while($row = $resultado->fetchArray(SQLITE3_ASSOC)){
                                    print '
                                    <option value="'.$row["usr_id"].'">'.$row["usr_id"].' - '.$row["usrnme"].'</option>
                                    ';
                                }
                            ?>
                        </select>    
                        
                        </div>
                    
                </div>
            </form>
            <div>     
                <?php
                    if(!empty($msg)){
                        print '<hr>'.$msg;
                    }
                ?>
            </div>
            <hr>
            <div style="
                position: initial;
                margin: auto;
                height: 70vh;
                width: 70vw;">
                <div class="chart-container">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php print ''.$table.'';?>
</div>

<div>
<?php
    include('../template/template-rodape.php');
?>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
		responsive: true,
        data: {
        labels: ['<?php print $labels_script;?> '],
        datasets: [{
            label: 'Operações',
            backgroundColor: [
                'rgb(0, 86, 145)',
                'rgb(0, 142, 207)',
                'rgb(0, 168, 176)',
                'rgb(120, 190, 32)',
                'rgb(0, 98, 73)',
                'rgb(185, 2, 118)',
                'rgb(80, 35, 127)',
                'rgb(82, 95, 107)',
                'rgb(191, 192, 194)',
                'rgb(0, 86, 145)',
                'rgb(0, 142, 207)',
                'rgb(0, 168, 176)',
                'rgb(120, 190, 32)',
                'rgb(0, 98, 73)',
                'rgb(185, 2, 118)',
                'rgb(80, 35, 127)',
                'rgb(82, 95, 107)',
                'rgb(191, 192, 194)'
            ],
            borderColor: 'rgb(255, 255, 255)',
            data: [' <?php print $data_perc_script;?> ']
        }]
    },

    // Configuration options go here
    options: {
        layout: {
            padding: {
                responsive: true
            }
        }
    }
    });
</script>
</div>
