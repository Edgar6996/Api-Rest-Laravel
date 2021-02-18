<?php


namespace App\Reports;


use App\Core\Helpers\FechasHelper;

use App\Models\Becado;
use Carbon\Carbon;

class BecadosReport extends AppReportPdf
{
    private $lista;
    public function __construct( )
    {
        parent::__construct();

        $this->lista = Becado::activos()
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

    }



    function generate(): string
    {
        ob_start(); ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>body{font-size: 16px;color: black;}</style>
            <title><?=$this->FILE_NAME?></title>
            <style type="text/css">
                <?php self::includeStyles();  ?>
            </style>
        </head>
        <body>

        <header style="height: 80px !important;">
            <table width="100%">
                <tr>
                    <td>
                        <h3>Comedor UNCAus</h3>
                    </td>
                    <td  align="right">
                        <img src="<?=$this->APP_LOGO_1?>" alt="" style="width: 120px">

                    </td>

                </tr>
            </table>
        </header>

        <footer>
            <b><?=$this->APP_NAME?></b> &nbsp;|&nbsp;
            <a href="<?=$this->APP_URL?>"><?=$this->APP_URL?></a>
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

            <table width="100%" style="margin-bottom: 10px !important;">
                <tr>
                    <td colspan="2">
                        <h4 style="margin-bottom: 6px !important;">LISTA DE BECADOS</h4>

                    </td>

                </tr>
                <tr>
                    <td>
                        <span style="font-size: 12px"></span>
                    </td>
                    <td width="100px" align="right">
                        <span style="font-size: 12px"><?= now()->format('d/m/Y') ?></span>
                    </td>
                </tr>

            </table>






            <table class="table-style-1 page-no-break" width="100%">

                <thead>
                <tr>

                    <th>Nombre Completo</th>
                    <th>D.N.I.</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Autorizado por</th>
                </tr>
                </thead>

                <tbody>

                <?php
                foreach ($this->lista as $p):?>

                    <tr >
                        <td width="130" align="left"><?= $p->apellidos ?>, <?= $p->nombres ?></td>
                        <td width="30px" align="center"><?= $p->dni ?></td>
                        <td align="center"><?= $p->email ?></td>
                        <td align="center"><?= $p->telefono ?></td>
                        <td align="center"><?= ucwords(mb_strtolower($p->autorizado_por)) ?></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>












            <!--        <div class="page_break"></div>-->
        </main>

        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
