<?php


namespace App\Reports;


use App\Core\Helpers\FechasHelper;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\FontMetrics;
use Dompdf\Options;

abstract class AppReportPdf
{
    const PATH_STYLE_FILE = "reportes/estilos-pdf.css";
    protected $APP_NAME = "Comedor UNCAus";
    protected $APP_URL;
    protected $FILE_NAME = "";

    protected $APP_LOGO_1;

    const WATERMARK = "";

    public function __construct()
    {
        $this->APP_URL = config('app.url');

        $this->APP_LOGO_1 = public_path("assets/logo-uncaus.png");
        #$this->APP_LOGO_1 = url("/assets/images/logo.png");
        #$this->APP_LOGO_1 = resource_path("img/logo.png");
        #dd($this->APP_LOGO_1);
    }

    abstract protected function generate(): string;


    protected function includeStyles(){
        include  resource_path(self::PATH_STYLE_FILE) ;
    }

    private function tmp()
    {
        $pdf = \App::make('dompdf.wrapper');


        $pdf->setOptions([
            'isPhpEnabled' => true,
            'setIsRemoteEnabled' => true,
            'defaultFont' => 'sans'
        ]);
        # $options->setIsRemoteEnabled(true);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        $pdf->setPaper('A4')
            ->setOptions([
                'tempDir' => public_path(),
                'chroot'  => public_path(),
            ]);
    }
    public function download($fileName = '')
    {
        if(!$fileName){
            $fileName = 'report';
        }
        $this->FILE_NAME = $fileName;

        $html = $this->generate();

        $op = new Options([
            'isPhpEnabled' => true,
            'setIsRemoteEnabled' => true,
            'defaultFont' => 'sans',
            'tempDir' => public_path(),
            'chroot'  => public_path(),
        ]);

        $pdf = new DomPdf();

        $pdf->loadHTML($html);

        $pdf->setPaper('A4')
            ->setOptions($op);

        $pdf->render();

        $canvas = $pdf->getCanvas();

        $fontMetrics = new FontMetrics($canvas, $op);

        $w = $canvas->get_width();
        $h = $canvas->get_height();

        $font = $fontMetrics->getFont('sans-serif');

        $text = self::WATERMARK;

        $size = 100;
        $txtHeight = $fontMetrics->getFontHeight($font, $size);
        $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

        $canvas->set_opacity(.05);

        $x = (($w-$textWidth)/1.2);
        $y = (($h-$txtHeight)/1.6);


        /**
         * Writes text at the specified x and y coordinates.
         *
         * @param float $x
         * @param float $y
         * @param string $text the text to write
         * @param string $font the font file to use
         * @param float $size the font size, in points
         * @param array $color
         * @param float $word_space word spacing adjustment
         * @param float $char_space char spacing adjustment
         * @param float $angle angle
         */
        //text($x, $y, $text, $font, $size, $color = array(0, 0, 0), $word_space = 0.0, $char_space = 0.0, $angle = 0.0);

        $canvas->text($x, $y, $text, $font, $size, [0,0,0],0.0,0,-45);

         $pdf->stream('invoice',[
            "Attachment" => false
        ]);
         die();
    }


    /**
     * @param Carbon $date
     */
    protected function parseDate($date)
    {
        return $date->format(FechasHelper::LOCAL_FORMAT_DATE);
    }
}
