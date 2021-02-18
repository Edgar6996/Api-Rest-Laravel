<?php

use App\Core\Helpers\FechasHelper;
use Carbon\Carbon;

/**
 *
 * @var string $FILE_NAME
 */

$VAR_LISTADO = [];


try {
    $fecha = new Carbon();
    $fecha = $fecha->format(FechasHelper::LOCAL_FORMAT_DATE);
} catch (Exception $e) {
    $fecha = "-------";
}

$VAR_TITLE = "TITULO DEL REPORTE";
$VAR_CODIGO = "CODIGO";
$VAR_APP_NAME = "MyApp";

$VAR_DESCRIPCION = "DESCRIPCION DEL REPORTE";
$VAR_INFO_2 = "VAR INFO 2";
$VAR_WEB_URL = "http://www.webreporte.com";

ob_start();


?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='utf-8'>
        <style>body{font-size: 16px;color: black;}</style>
        <title><?=$FILE_NAME?></title>
        <style type="text/css">
            <?php include  resource_path("reportes/estilos-pdf.css")   ?>



        </style>
    </head>
    <body>

    <header>
        <table width="100%">
            <tr>
                <td >
                    <b id="h-title"><?=$VAR_TITLE?></b>
                </td>
                <td align="right"></td>
                <td width="15%" align="right"></td>
            </tr>
            <tr>
                <td >
                    <span>Fecha: <b><?=$fecha?></b></span>
                </td>
                <td align="right">
                    <span>Código:</span>
                </td>
                <td align="right">
                    <span>  <b><?=$VAR_CODIGO?></b></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>
                        <i><?=$VAR_DESCRIPCION?></i>
                    </span>
                </td>
                <td align="right">

                </td>
                <td align="right">
                    <span><b><?=$VAR_INFO_2?></b></span>
                </td>
            </tr>

        </table>
    </header>

    <footer>
        <b><?=$VAR_APP_NAME?></b> &nbsp;|&nbsp;
        <a href="<?=$VAR_WEB_URL?>"><?=$VAR_WEB_URL?></a>
    </footer>

    <script type="text/php">
    if (isset($pdf))
    {
        $x = 550;
        $y = 820;
        $text = "{PAGE_NUM} de {PAGE_COUNT}";
        $font = $fontMetrics->get_font("helvetica");
        $size = 10;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
            </script>

    <main>
        <table class="table-style-1 page-no-break" width="100%">

            <thead>
            <tr>
                <th>col 1</th>
                <th>col 2</th>
                <th>col 3</th>
                <th>col 4</th>


            </tr>
            </thead>
            <tbody>
<!--            --><-?//phpforeach ($VAR_LISTADO as $p):?->

                <tr >
                    <td align="right">texto col1</td>
                    <td align="right">texto col2</td>
                    <td align="right">texto col3</td>
                    <td align="right">texto col4</td>

                </tr>
<!--            <-?php endforeach; ?->-->

            </tbody>
        </table>

<!--        <div class="page_break"></div>-->
    </main>

    </body>
    </html>
<?php
$html = ob_get_clean();
