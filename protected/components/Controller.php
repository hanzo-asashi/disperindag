<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     *             meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     *            be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     *            for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $baseUrl;

    public function init()
    {
        $this->baseUrl = yii::app()->getBaseUrl(true);
        Yii::app()->dateFormatter->attachBehavior('ExtendedDateTimeFormatting', 'ext.ExtendedDateTimeFormattingBehavior.ExtendedDateTimeFormattingBehavior');
        // Tweaks for Ajax Requests
        Yii::app()->user->loginRequiredAjaxResponse = "<script>window.location.href = '" . Yii::app()->user->id . "';</script>";
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap = array(
                'jquery.js' => false,
                'jquery.min.js' => false,
            );
        }
        if(YII_DEBUG) {
            if (isset($_GET['d']) && $_GET['d'] == 'clear') {
                Yii::app()->cache->flush();
            }
        }
    }

    protected function initAjaxCsrfToken()
    {
        Yii::app()->clientScript->registerScript('AjaxCsrfToken', ' $.ajaxSetup({
			data: {"' . Yii::app()->request->csrfTokenName . '": "' . Yii::app()->request->csrfToken . '"},
			cache:false
			});', CClientScript::POS_HEAD);
    }

    public function pdf($content, $namafile, $paper_size = '', $orientation = 'P', $border = true, $title = '', $subject = '', $mode = 'D', $watermark = false)
    {
        $margin = 20;
        $namafile = preg_replace('/\.pdf$/', '', $namafile);

        $mL = $mR = $mT = $mB = $margin;
        // $mB = $margin + 50;
        $mH = 0;
        $mF = 0;
        if (!$paper_size)
            $paper_size = array(215, 330);
        Yii::import('system.docotel.cms.extensions.mpdf.mPDF');
        $mpdf = new mPDF('', $paper_size, 0, '', $mL, $mR, $mT, $mB, $mH, $mF, $orientation);

        $mpdf->SetDefaultFont('Arial');
        $mpdf->SetProtection(array('print', 'print-highres'), '', md5(time()), 128);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor('Makarim & Taira');
        $mpdf->SetCreator('News');
        $mpdf->SetSubject($subject);
        $mpdf->h2toc = array('H4' => 0, 'H5' => 1);
        //$mpdf->setFooter('{PAGENO}');

        // $stylesheet = file_get_contents(Yii::app()->getBaseUrl(true).'/themes/flatlab/assets/css/bootstrap.min.css'); // external css
        // $mpdf->WriteHTML($stylesheet,1);
        // echo $content; exit;
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile . '.pdf', $mode);
        if ($mode === 'D' or $mode === 'I')
            exit;
    }

    public function jsonParse($val)
    {
        $data = array(
            'status' => 500,
            'message' => 'no data',
            'value' => '',
        );

        if ($val) {
            $data['status'] = 200;
            $data['message'] = 'success';
            $data['value'] = $val;
        }

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }

    public function convJson($arr)
    {
        if (is_array($arr) || is_object($arr)) {
            foreach ($arr as $k => $v) {
                if (is_array($v) || is_object($v)) {
                    $arr[$k] = json_encode($v);
                }
            }
        }

        return $arr;
    }

    public function convDate($str, $flag = false)
    {
        if (!$flag && preg_match('|([\d]{2})-([\d]{2})-([\d]{4})|', $str)) {
            $str = date('Y-m-d', strtotime($str));
        } elseif ($flag == true && preg_match('|([\d]{4})-([\d]{2})-([\d]{2})|', $str)) {
            $str = date('d-m-Y', strtotime($str));
        }

        return $str;
    }

    public function convString($arr)
    {
        $data = array();

        if (is_array($arr) || is_object($arr)) {
            foreach ($arr as $k => $v) {
                if (is_string($v)) {
                    if (strpos($v, '{') !== false || strpos($v, '[') !== false) {
                        $arr[$k] = json_decode($v, 512);
                    }
                }

                if (is_array($v)) {
                    foreach ($v as $kunci => $d) {
                        $data[] = (array) $d;
                    }

                    $arr[$k] = $data;
                }
            }
        }

        return $arr;
    }
}
