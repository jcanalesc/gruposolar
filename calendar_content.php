<?
    require_once("header.php");
    $res = mysql_query("select * from remates where (publico = true or en_curso = true) order by banner_size, fecha, hora", dbConn::$cn);
    $remates = array();
    if ($res !== false && mysql_num_rows($res) > 0) while($row = mysql_fetch_assoc($res))
    {
        $remates[] = array
                    (
                        'url' => "frontis.php?starred={$row['id_remate']}",
                        'tipo' => "{$row['tipo']}",
                        'estado' => "Finalizado",
                        'lugar' => "{$row['lugar']}",
                        'ciudad' => "{$row['ciudad']}",
                        'fecha' => "{$row['fecha']}",
                        'hora' => "{$row['hora']}",
                        'comision' => "{$row['comision']}%",
                        'tipo_productos' => "{$row['tipo_productos']}",
                        'contacto' => "{$row['contacto']}",
                        'visible' => $row['publico'] ? "Disponible" : "No disponible",
                        'id' => $row['id_remate'],
                        'banner' => $row['banner'],
                        'banner_size' => $row['banner_size']
                    );
    }
    //var_dump($remates);
                    $meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
                    
                    list($day, $month, $year, $monthdays) = explode(",", date("j,n,Y,t", time()));
                    if (isset($_POST['mes']) and $_POST['mes'] != 0)
                    {
                        list($day, $month, $year, $monthdays) = explode(",", date("j,n,Y,t", mktime(0,0,0,$month + $_POST['mes'], $day, $year)));
                    }
                    $mes = $meses[$month - 1];
                    echo $mes;
                ?>
                    <table>
                    <?
                        $days = "LMMJVSD";
                        echo "<tr class=\"calendar-days\">";
                        for ($k = 0; $k < 7; $k++)
                        {
                            echo "<td>".$days[$k]."</td>";
                        }
                        echo "</tr>";
                        $ndays = $monthdays;
                        $firstdayofmonth = (int)date("N",mktime(0,0,0,$month,1,$year));
                        $initial_blanks = $firstdayofmonth - 1;
                        for ($i = 1 - $initial_blanks; $i <= $ndays; $i++)
                        {
                            $buff = "";
                            for ($j = 0; $j < 7; $j++)
                            {
                                $remateaqui = false;
                                foreach($remates as $rm)
                                {
                                    if ($rm['fecha'] == date("Y-m-d", mktime(0,0,0,$month,$i, $year)))
                                    {
                                        $remateaqui = true;
                                        break;
                                    }
                                }
                                
                                $buff = $buff.($i == $day && (!isset($_POST['mes']) || $_POST['mes'] == 0) ? "<td class=\"calendar-today\">" : "<td>").($remateaqui ? "<span class='rematemarcado' data-date='{$rm['fecha']}'>" : "").($i <= $ndays && $i > 0 ? $i : "").($remateaqui ? "</span>" : "")."</td>";
                                $i++;
                            }
                            echo "<tr>$buff</tr>";
                            $i--;
                        }
                            
                    ?>
                    </table>
