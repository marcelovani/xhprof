<?php

namespace Xhprof\Controller;

class Chart
{
    /**
     * Returns chart markup.
     *
     * @return string
     *   The chart markup.
     */
    public function showChart($rs, $flip = false)
    {

        $dataPoints = "";
        $ids = array();
        $arCPU = array();
        $arWT = array();
        $arPEAK = array();
        $arIDS = array();
        $arDateIDs = array();
        $runs = new XhprofRuns();

        while ($row = $runs->getNextAssoc($rs)) {
            $date[] = "'" . date("Y-m-d", $row['timestamp']) . "'";

            $arCPU[] = $row['cpu'];
            $arWT[] = $row['wt'];
            $arPEAK[] = $row['pmu'];
            $arIDS[] = $row['id'];

            $arDateIDs[] = "'" . date("Y-m-d", $row['timestamp']) . " <br/> " . $row['id'] . "'";
        }

        $date = $flip ? array_reverse($date) : $date;
        $arCPU = $flip ? array_reverse($arCPU) : $arCPU;
        $arWT = $flip ? array_reverse($arWT) : $arWT;
        $arPEAK = $flip ? array_reverse($arPEAK) : $arPEAK;
        $arIDS = $flip ? array_reverse($arIDS) : $arIDS;
        $arDateIDs = $flip ? array_reverse($arDateIDs) : $arDateIDs;

        $dateJS = implode(", ", $date);
        $cpuJS = implode(", ", $arCPU);
        $wtJS = implode(", ", $arWT);
        $pmuJS = implode(", ", $arPEAK);
        $idsJS = implode(", ", $arIDS);
        $dateidsJS = implode(", ", $arDateIDs);


        ob_start();
        require(XHPROF_LIB_ROOT . "/templates/chart.phtml");
        $stuff = ob_get_contents();
        ob_end_clean();
        return array($stuff, "<div id=\"container\" style=\"width: 1000px; height: 500px; margin: 0 auto\"></div>");
    }

}