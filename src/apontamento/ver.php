<td colspan="10">
<div class="card" style="margin: 1em; padding: 1em; margin-top:3em;">
    <div class="card-body">
        <form id="apontamento-linha" >
            <div class="row">
                <!-- Começa: Data -->
                <div class="col-md-4 mb-3">
                    <label for="adddte">Data</label>
                    <div class="input-group">
                    <input type="date" class="form-control" id="adddte" value="<?php print $_GET['adddte']; ?>">
                    <div class="invalid-feedback" style="width: 100%;">
                        Data Obrigatória.
                    </div>
                    </div>
                </div>
                <!-- Termina: Data -->

                <!-- Começa: Hora Inicio -->
                <div class="col-md-4 mb-3">
                    <label for="fr_tim">Hora Início</label>
                    
                    <div class="input-group">
                    <input type="text" class="form-control" id="fr_tim" value="<?php print $_GET['fr_tim']; ?>" >
                    <div class="invalid-feedback" style="width: 100%;">
                        Horário Obrigatório.
                    </div>
                    </div>
                    
                </div>
                <!-- Termina: Hora Inicio -->

                <!-- Começa: Hora Fim -->
                <div class="col-md-4 mb-3">
                    <label for="to_tim">Hora Fim</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="to_tim" value="<?php print $_GET['to_tim']; ?>" >
                    <div class="invalid-feedback" style="width: 100%;">
                        Horário Obrigatório.
                    </div>
                    </div>
                </div>
                <!-- Termina: Hora Fim -->

                <!-- Começa: Produto -->
                <div class="col-md-6 mb-3">
                    <label for="prd_id">Produto</label>
                        <select class="custom-select d-block w-100" id="prd_id" >
                        <option value="">Selecione...</option>
                        <?php
                            $db = new SQLite3('../sqlite/apontamentos.db');
                            
                            $s_tblprd = "
                                    SELECT prd_id, prdnme FROM usrprd GROUP BY prd_id, prdnme
                                ";
                                $resultado = $db->query($s_tblprd);
                                while($row = $resultado->fetchArray(SQLITE3_ASSOC)){
                                    if($_GET['prdnme'] == $row["prdnme"]){
                                        print '
                                        <option value="'.$row["prd_id"].' selected ">'.$row["prdnme"].'</option>
                                        ';
                                    }
                                    print '
                                    <option value="'.$row["prd_id"].' ">'.$row["prdnme"].'</option>
                                    ';
                                    
                                }
                        ?>
                        </select>
                    <div class="invalid-feedback">
                        Campo Produto é Obrigatório.
                    </div>
                </div>
                <!-- Termina: Produto -->

                <!-- Começa: Operação -->
                <div class="col-md-3 mb-3">
                    <label for="opr_id">Operações</label>
                    <select class="custom-select d-block w-100" id="opr_id" required>
                    <option value="">Selecione...</option>
                    <?php
                        $db = new SQLite3('../sqlite/apontamentos.db');

                        $s_tblprd = "
                                SELECT opr_id, oprnme FROM usropr GROUP BY opr_id, oprnme
                            ";
                            $resultado = $db->query($s_tblprd);
                            while($row = $resultado->fetchArray(SQLITE3_ASSOC)){
                                if($_GET['oprnme'] == $row["oprnme"]){
                                    print '
                                    <option value="'.$row["opr_id"].' selected">'.$row["oprnme"].'</option>
                                    ';
                                }
                                print '
                                <option value="'.$row["opr_id"].'">'.$row["oprnme"].'</option>
                                ';
                            }
                    ?>
                    </select>
                    <div class="invalid-feedback">
                        Campo Operação é Obrigatório.
                    </div>
                </div>
                <!-- Termina: Operação -->

                    <!-- Começa: País -->
                    <div class="col-md-3 mb-3">
                        <label for="cty_id">País</label>
                        <select class="custom-select d-block w-100" id="cty_id" required>
                        <option value="">Selecione...</option>
                        <?php
                            $db = new SQLite3('../sqlite/apontamentos.db');

                            $s_tblprd = "
                                    SELECT cty_id, ctynme FROM usrcty GROUP BY cty_id, ctynme
                                ";
                                $resultado = $db->query($s_tblprd);
                                while($row = $resultado->fetchArray(SQLITE3_ASSOC)){
                                    if($_GET['oprnme'] == $row["ctynme"]){
                                        print '
                                        <option value="'.$row["cty_id"].' selected">'.$row["ctynme"].'</option>
                                        ';
                                    }
                                    print '
                                    <option value="'.$row["cty_id"].'">'.$row["ctynme"].'</option>
                                    ';
                            }
                    ?>
                    
                    </select>
                    <div class="invalid-feedback">
                        Campo País é Obrigatório.
                    </div>
                </div>
                <!-- Termina: País -->

                <!-- Começa: Solicitante -->
                <div class="col-md-5 mb-3">
                    <label for="usrask">Solicitante</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="usrask" placeholder="Nome" value="<?php print $_GET['usrask']; ?>">
                    <div class="invalid-feedback">
                        Nome do Solicitante é Obrigatório.
                    </div>
                    </div>
                </div>
                <!-- Termina: Solicitante -->

                <!-- Começa: Observação -->
                <div class="col-md-7 mb-3">
                    <label for="usrobs">Observação</label>
                    <textarea  rows="3" class="form-control" id="usrobs" placeholder="Observações."><?php print $_GET['usrobs']; ?></textarea>
                </div>
                <!-- Termina: Observação -->

                <!-- Começa: Usuário -->
                <input type="text" class="form-control" id="usr_id" placeholder=""  value="<?php print $_GET['usr_id'];?>" hidden>
                <!-- Termina: Usuário -->
                <!-- Começa: ID -->
                <input type="text" class="form-control" id="usr_id" placeholder=""  value="<?php print $_GET['id'];?>" hidden>
                <!-- Termina: ID -->
            </div>
            <button type="submit" class="btn btn-primary" id="salvar" style="width: 33%; float: right;">Salvar</button>
        </form>
            
    </div>

    <div role="alert" id="res"> 
        <!-- class="alert alert-primary" -->
    </div>
</div>
</td>
<script src="apontamento-update-exe.js"></script>