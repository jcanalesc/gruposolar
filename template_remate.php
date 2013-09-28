                <div class="remate-<?= $rem['banner_size'] ?> remate <?= ($rem['tipo'] == "Presencial" ? "remate-p" : "") ?>" data-sala="<?= $rem['sala'] ?>" <?= $presencial ?>>
                    
                    
                    <span class="remate-reminder">
                        <a href="#" data-idr="<?= $rem['id'] ?>">MARQUE AQUÍ</a> PARA AVISARLE DEL REMATE 2 HORAS ANTES
                    </span>
                <?
					 	$partes = explode("/", $rem['banner']);
						$fn = $partes[count($partes)-1];
						$fn = "orig_".$fn;
						$partes[count($partes)-1] = $fn;
						$rem['banner'] = implode("/", $partes);

                        $data_rsm = $rem['requiere_auth'] == "1" ? "data-no-auth=\"{$rem['texto_usuario_noauth']}\"" : "";
						
                ?>
                    <img data-url="<?= $rem['url'] ?>" <?= $data_rsm ?> class="foto-<?= $rem['banner_size'] ?>" src="<?= $rem['banner'] ?>" /><br />
                <? if ($rem['banner_size'] == "big"  || $rem['banner_size'] == "extra") { ?>
                    <span class="remate-status">
                        <u>ESTADO:</u> <?= $rem['estado'] ?> <span class="bull2 <?= $color ?>">&bull;</span>
                    </span>
                <? } ?>
    
                <? if ($rem['banner_size'] != "small"): ?>
                <!--    
                    <span class="remate-property"><b>LUGAR:</b><?= $rem['lugar'] ?></span>
                    <span class="remate-property"><b>CIUDAD:</b><?= $rem['ciudad'] ?></span>
                    <span class="remate-property"><b>FECHA:</b><?= $rem['fecha'] ?></span>
                    <span class="remate-property"><b>HORA:</b><?= $rem['hora'] ?></span>
                    <span class="remate-property"><b>SALA:</b><?= $rem['sala'] ?></span>
                -->
                <? endif; ?>
                <? if ($rem['tipo'] == "Presencial"): ?>
                    <span class="remate-property"><b>REMATE:</b> <?= $rem['tipo'] ?></span>
                <? endif; ?>
                
                <? if ($rem['banner_size'] == "big" || $rem['banner_size'] == "extra"): ?>
                    <span class="remate-property"><b>PRODUCTOS:</b><?= $rem['tipo_productos'] ?></span>
                    <span class="remate-property"><b>COMISION:</b><?= $rem['comision'] ?></span>
                 <!--   <span class="remate-property"><b>A LA VISTA:</b><?= $rem['visible'] ?></span> -->
                    <span class="remate-property"><b>CONTACTO:</b><?= $rem['contacto'] ?></span>
                    
                <? endif; ?>
                <? if ($rem['tipo'] == "Online" && ($rem['banner_size'] == "big"  || $rem['banner_size'] == "extra")): ?>
                    <div style="text-align: center;" data-var="<?= $rem['id'] ?>" data-room="<?= $rem['sala'] ?>">
                        <button class="panel go-remate" <?= $data_rsm ?> data-url="<?= "/{$rem['sala']}/remate.php?id={$rem['id']}" ?>"><img src="pr2-img/1t.png" valign="middle" />Ingresar al Remate Online</button>
                        <button class="panel go-galeria"><img src="pr2-img/3t.png" valign="middle" />Ver Galería</button>
                        <button class="panel go-proc" data-proc="<?= $rem['proc'] ?>"><img src="pr2-img/2t.png" valign="middle" />Ver Procedimiento</button>
                        
                    </div>
                <? endif; ?>
                </div>
