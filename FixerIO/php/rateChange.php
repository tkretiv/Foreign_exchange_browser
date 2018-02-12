    <?php 
    class CurlRequest {

      var $sURL = "https://api.fixer.io/latest";
      var $mWrongDateFormat = ''; 
      var $mSimpleDateFormat  = ''; 
      var $m30DaysDateFormat  = ''; 

      function CheckDateFormat ($DateFormat) 
      { 
          $this->mWrongDateFormat = $DateFormat; 
          list($year, $month, $day) = split ('[/.\-]', $this->mWrongDateFormat); 
          if (checkdate ((int)$month, (int)$day, (int)$year )) 
          { 
              $this->mSimpleDateFormat = $year . '-' . $month . '-' . $day; 
              $newDate = new DateTime($this->mSimpleDateFormat);
              $newDate->sub(new DateInterval('P30D'));
              $this->m30DaysDateFormat = $newDate->format('Y-m-d'); 
              return true;
          } else 
          { 
              echo "DateFormat Error! ";  
              return false; 
          } 
      }    

      public function requestExec($Date) 
      {
          if ($this->CheckDateFormat($Date)) 
          {
              $this->sURL = 'https://api.fixer.io/'.$this->mSimpleDateFormat;
           
          $ch = curl_init(); 
    
          curl_setopt_array($ch, array(
          CURLOPT_URL => $this->sURL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
                      "cache-control: no-cache",
                      "Content-Type: application/json"
                      ),
          ));

          $data = curl_exec($ch);

          if ($data === FALSE) 
          {  
              echo "cURL Error: " . curl_error($ch);  
              return false;  
          } 
          $result=json_decode($data, true);
          curl_close($ch); 
          return $result;
        }
        else return false;
      } 

      public function Cur30DaysChange($Date)
      {
        if ($this->CheckDateFormat($Date)) 
          { 
            $datePast =  $this->m30DaysDateFormat;

            $RatesLastData = $this->requestExec($Date);
           
            $RatesPastData = $this->requestExec($datePast);
            $RatesLast=$RatesLastData["rates"];
            $RatesPast=$RatesPastData["rates"];
          } 
          else
            {
              print "Error: Wrong date.";
              die();
            }
           
            ksort($RatesLast);
            ksort($RatesPast);
            //reset($RatesPast);
        foreach($RatesLast as $currency => $val) 
        {
          if(array_key_exists($currency, $RatesPast))
             {
                $count1 = $val - $RatesPast[$currency];
                $count = $count1 / $RatesPast[$currency];
                $rateProc[$currency] = round($count*100,2);
              } 
             
        }
        return $rateProc;
      }
    }

 $cRequest = new CurlRequest();
      if(isset($_REQUEST["clientRequest"]))
    {
      $data = $cRequest->Cur30DaysChange($_REQUEST["dateRequest"] ? $_REQUEST["dateRequest"] : date('Y-m-d'));
      if (!$data) 
      {
        return "Error !!!";
        die;
      }
    $rates = $data;

    arsort($rates);
      echo json_encode($rates);
      //return $rates;    
    }
else {
     $data = $cRequest->Cur30DaysChange($argv[1] ? $argv[1] : date('Y-m-d'));
      if (!$data) 
      {
        return "Error !!!";
        die;
      }
    $rates = $data;

    arsort($rates);
    print "\n----- RATES: ----\n";
    print "\xDA-----\xC2---------\xBF\n";
    print "\xB3 CUR \xB3   Rate  \xB3\n";
     foreach($rates as $currency => $val) {
        print "\xC3-----\xC5---------\xB4\n\xB3";
        printf ("%'. 5s",$currency);
        print "\xB3";
        printf("%'. 9g", $val.'%');
        print "\xB3\n";
      }
    print "\xC0-----\xC1---------\xD9\n";
  }
?>