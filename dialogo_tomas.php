	<p id="leyenda">
	<span class="tex"><span class="red">¡FELICITACIONES!, USTED POSEE LA MEJOR OFERTA:</span><br /><span class="tiny">Seleccione a continuación cuantas unidades adicionales a las que adjudicó desea tomar.</span></span>
	<span class="tex red" id="texto_p" style="font-size: 16px;">LOTE %nlote% DISPONIBLE</span>
	</p>
	<form id="derechof" method="post" action="#" onsubmit="envia_derecho(); return false;">
	<div class="numerico" style="margin-top: 5px;">
		<div class="filabuttons">
			<input type="radio" name="cantidad" value="1" id="c1" /><label for="c1">1</label>
			<input type="radio" name="cantidad" value="2" id="c2" /><label for="c2">2</label>
			<input type="radio" name="cantidad" value="3" id="c3" /><label for="c3">3</label>
         <input type="radio" name="cantidad" value="4" id="c4" /><label for="c4">4</label>
		</div>
		<div class="filabuttons">
         <input type="radio" name="cantidad" value="5" id="c5" /><label for="c5">5</label>
			<input type="radio" name="cantidad" value="6" id="c6" /><label for="c6">6</label>
			<input type="radio" name="cantidad" value="7" id="c7" /><label for="c7">7</label>
			<input type="radio" name="cantidad" value="8" id="c8" /><label for="c8">8</label>
		</div>
		<!-- <input type="submit" value="ADJUDICAR" disabled="disabled"/> -->
	</div>
   <div id="cantidadd" style="font-weight: bold; width: 170px;">STOCK DISPONIBLE: <span id="cantidadp" class="red"></span></div>
   <div style="float: right;">TIEMPO:<div id="tiempotoma" class="red bigtext"></div></div>
   <div id="pad_perdedor" style="float: left;">
      <input type="hidden" name="cantidad" value="1" id="cantidadpordefecto"/>
      <input type="submit" value="TOMAR UNIDAD" id="but2" style="margin-bottom: 30px;"/><br />
      <input type="button" class="but3" value="OMITIR" onclick="cierraDialogo();" style="float: left;" />
   </div>
   <input id="gcancel" type="button" class="but3" value="NO DESEO ADICIONAL" onclick="cierraDialogo();"/>
	</form>
