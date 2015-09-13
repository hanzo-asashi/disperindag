<?php

    /**
     * Created by PhpStorm.
     * User: hanse
     * Date: 9/12/2015
     * Time: 10:57 PM
     */
    class Helper
    {
        public static function ambil_kata( $text, $jumlah) {
            return implode(
                '',
                array_slice(
                    preg_split(
                        '/([\s,\.;\?\!]+)/',
                        $text,
                        $jumlah*2+1,
                        PREG_SPLIT_DELIM_CAPTURE
                    ),
                    0,
                    $jumlah*2-1
                )
            );
        }

        public static function cekParam($param) {
            if (!empty($param)) {
                $url = array();
                foreach ($param as $key => $value) {
                    if (!empty($value)) {
//					if ($url[$key] == 'page') {
                        //						# code...
                        //					}
                        $url[$key] = $value;
                    }
                }
                return $uri = http_build_query($url) . "\n";
            }
        }

        public static function rupiah_display($var, $null = true, $fractional = false) {
            $rupiah = self::_format_number($var, $null, $fractional);

            // var_dump($rupiah);
            return $rupiah != "" && $rupiah != "N/A" ? "Rp. " . $rupiah : $rupiah;

            //    return 'Rp. ' . number_format($var);
            //    return 'Rp. ' . money_format("%i", $var);
        }

        public static function dump($var, $label = 'Dump', $echo = true) {
            // Store dump in variable
            ob_start();
            var_dump($var);
            $output = ob_get_clean();

            // Add formatting
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 0px 0 10px; text-align: left; position:relative; z-index: 999999;">' . $label . ' => ' . $output . '</pre>';

            // Output
            if ($echo == true) {
                echo $output;
            } else {
                return $output;
            }
        }

        public static function dd($var, $label = 'Dump', $echo = true) {
            self::dump($var, $label, $echo);
            exit;
        }

        public static function _format_number($var, $null = true, $fractional = false) {
            if ($null === true && $var == 0) {
                return "N/A";
            }

            if ($null === false && ($var == 0 || $var == "")) {
                return "";
            }

            if ($fractional) {
                $var = sprintf('%.2f', $var);
                // var_dump($var);exit();
            }
            while (true) {
                $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1.$2', $var);
                if ($replaced != $var) {
                    $var = $replaced;
                } else {
                    break;
                }
                // var_dump($var);
            }

            return $var;
        }

        public static function convertNilai($var) {
            $nilai = self::rupiah_display(substr($var, 0, -3));
            $trim = str_replace('Rp. ', '', $nilai);
            $explode = explode('.', $trim);
            $count = count($explode);
            if ($count == 4) {
                if ($explode[1] == '000') {
                    $nominal = $explode[0];

                    return $nominal . ' M';
                } else {
                    //$str_count = substr_count($explode[1], '0');
                    $exp = substr($explode[1], 0, -2);
                    $nominal = $explode[0] . ',' . $exp;

                    return $nominal . ' M';
                    // var_dump($exp);exit();
                }
            } elseif ($count == 5) {
                if ($explode[1] == '000') {
                    $nominal = $explode[0];

                    return $nominal . ' T';
                } else {
                    $exp = substr($explode[1], 0, -2);
                    $nominal = $explode[0] . ',' . $exp;

                    return $nominal . ' T';
                }
            } elseif ($count == 3) {
                if ($explode[1] == '000') {
                    $nominal = $explode[0];

                    return $nominal . ' Jt';
                } else {
                    $exp = substr($explode[1], 0, -2);
                    $nominal = $explode[0] . ',' . $exp;

                    return $nominal . ' Jt';
                }
            }
        }

        public static function nominal($var) {
            //$tes  = '5000000000.00';
            $var = substr($var, 0, -3);
            $val = self::rupiah_display($var);
            $trim = str_replace('Rp. ', '', $val);
            $explode = explode('.', $trim);
            $count = count($explode);

            if ($count == 5) {
                if ($explode[1] == '000') {
                    $nom = $explode[0];

                    return $nom . ' T';
                } else {
                    $str_count = substr_count($explode[1], '0');
                    if ($str_count == 2) {
                        $nom = $explode[0] . '.' . $explode[1];
                        $nom_ = strstr($nom, '0', true);
                        $rep = str_replace('.', ',', $nom_);

                        return $rep . ' T';
                    } else {
                        $nom = $explode[0] . '.' . $explode[1];
                        $round = round($nom, 2);
                        $rep = str_replace('.', ',', $round);

                        return $rep . ' T';
                    }
                }
            } elseif ($count == 4) {
                if ($explode[1] == '000') {
                    $nom = $explode[0];

                    // $hasil = $nom . ' M';
                    return $nom . ' M';
                } else {
                    $str_count = substr_count($explode[1], '0');
                    if ($str_count == 2) {
                        $nom = $explode[0] . '.' . $explode[1];
                        $nom_ = strstr($nom, '0', true);
                        $rd = round($nom_);
                        //$rep = str_replace('.', ',', $nom_);
                        $rep = str_replace('.', ',', $rd);
                        //var_dump($nom_,$rd,$rep);exit;
                        // $hasil = $rep . ' M';
                        return $rep . ' M';
                    } else {
                        $nom = $explode[0] . '.' . $explode[1];
                        $round = round($nom, 2);
                        //var_dump($nom,$round);exit;
                        $rep = str_replace('.', ',', $round);

                        // $hasil = $rep . ' T';
                        return $rep . ' M';
                    }
                }
            } elseif ($count == 3) {
                if ($explode[1] == '000') {
                    $nom = $explode[0];

                    return $nom . ' Jt';
                } else {
                    $str_count = substr_count($explode[1], '0');
                    if ($str_count == 2) {
                        $str_count_ = substr_count($explode[1], '0');
                        if ($str_count_ == 2) {
                            $strstr = strstr($explode[1], '0', true);

                            //var_dump($strstr);exit;
                            return $explode[0] . ',' . $strstr . ' Jt';
                        } else {
                            $nom = $explode[0] . ',' . $explode[1];
                            $nom_ = strstr($nom, '0', true);

                            return $nom_ . ' Jt';
                        }
                    } else {
                        $nom = $explode[0] . '.' . $explode[1];
                        $round = round($nom, 2);
                        if ($round == 1000) {
                            $round = $round / 1000;
                            $rep = str_replace('.', ',', $round);

                            return $rep . ' M';
                            //var_dump($round,$nom,$str_count,$explode[0],$rep);exit;
                        } else {
                            $rep = str_replace('.', ',', $round);

                            return $rep . ' Jt';
                        }
                    }
                }
            } elseif ($count == 2) {
                if ($explode[1] == '000') {
                    $nom = $explode[0];

                    return $nom . ' Rb';
                } else {
                    $str_count = substr_count($explode[1], '0');
                    if ($str_count == 2) {
                        $str_count_ = substr_count($explode[1], '0');
                        if ($str_count_ == 2) {
                            $strstr = strstr($explode[1], '0', true);

                            return $explode[0] . ',' . $strstr . ' Rb';
                        } else {
                            $nom = $explode[0] . ',' . $explode[1];
                            $nom_ = strstr($nom, '0', true);

                            return $nom_ . ' Rb';
                        }
                    } else {
                        $nom = $explode[0] . '.' . $explode[1];
                        $round = round($nom, 2);
                        $rep = str_replace('.', ',', $round);

                        return $rep . ' Rb';
                    }
                }
            }
        }

        public static function convertNominal($val) {
            $kata = explode(' ', $val);
            $count = count($kata);
            if ($count == 2 && $kata[0] != '0') {
                if ($kata[1] == 'M') {
                    $explode = explode(',', $kata[0]);
                    if (strpos($kata[0], ',') !== false) {
                        $length = strlen($explode[1]);
                        $replace = str_replace(',', '', $kata[0]);

                        if ($length == 1) {
                            $float = $replace . '00000000';
                        } elseif ($length == 2) {
                            $float = $replace . '0000000';
                        }
                    } else {
                        $float = $kata[0] . '000000000';
                    }
                } elseif ($kata[1] == 'jt') {
                    $explode = explode(',', $kata[0]);
                    if (strpos($kata[0], ',') !== false) {
                        $length = strlen($explode[1]);
                        $replace = str_replace(',', '', $kata[0]);

                        if ($length == 1) {
                            $float = $replace . '00000';
                        } elseif ($length == 2) {
                            $float = $replace . '0000';
                        }
                    } else {
                        $float = $kata[0] . '000000';
                    }
                }
            } elseif ($count == 1 && $kata[0] != '-' && $kata[0] != '0') {
                $float = str_replace(array(',', '.'), '', $kata[0]);
            } else {
                $float = $kata[0];
            }

            return $float;
        }

        public static function setTimeZone($timezone) {

            date_default_timezone_set($timezone);
        }

        public static function getDomain($str) {
            $regex = "|http://([^/]+)/|";
            preg_match_all($regex, $str, $match_all);

            return isset($match_all[0][0]) ? $match_all[0][0] : '';
        }

        public static function isValidIp($ipAddress) {
            return filter_var($ipAddress, FILTER_VALIDATE_IP);
        }

        public static function getLpseHost($host) {
            if (self::isValidIp($host)) {
                return $host;
            } else {
                return "lpse." . $host . ".go.id";
            }
        }

        public static function bilangRatusan($x) {
            $kata = array('', 'Satu ', 'Dua ', 'Tiga ', 'Empat ', 'Lima ', 'Enam ', 'Tujuh ', 'Delapan ', 'Sembilan ');
            $string = '';
            $ratusan = floor($x / 100);
            $x = $x % 100;
            if ($ratusan > 1) {
                $string .= $kata[$ratusan] . "Ratus ";
            } else {
                if ($ratusan == 1) {
                    $string .= "Seratus ";
                }
            }
            $puluhan = floor($x / 10);
            $x = $x % 10;
            if ($puluhan > 1) {
                $string .= $kata[$puluhan] . "Puluh ";
                $string .= $kata[$x];
            } else {
                if (($puluhan == 1) && ($x > 0)) {
                    $string .= $kata[$x] . "Belas ";
                } else {
                    if (($puluhan == 1) && ($x == 0)) {
                        $string .= $kata[$x] . "Sepuluh ";
                    } else {
                        if ($puluhan == 0) {
                            $string .= $kata[$x];
                        }
                    }
                }
            }

            return $string;
        }

        public static function terbilang($x) {
            $x = number_format($x, 0, "", ".");
            $pecah = explode(".", $x);
            $string = "";
            for ($i = 0; $i <= count($pecah) - 1; $i++) {
                if ((count($pecah) - $i == 5) && ($pecah[$i] != 0)) {
                    $string .= self::bilangRatusan($pecah[$i]) . "Triliyun ";
                } else {
                    if ((count($pecah) - $i == 4) && ($pecah[$i] != 0)) {
                        $string .= self::bilangRatusan($pecah[$i]) . "Milyar ";
                    } else {
                        if ((count($pecah) - $i == 3) && ($pecah[$i] != 0)) {
                            $string .= self::bilangRatusan($pecah[$i]) . "Juta ";
                        } else {
                            if ((count($pecah) - $i == 2) && ($pecah[$i] == 1)) {
                                $string .= "Seribu ";
                            } else {
                                if ((count($pecah) - $i == 2) && ($pecah[$i] != 0)) {
                                    $string .= self::bilangRatusan($pecah[$i]) . "Ribu ";
                                } else {
                                    if ((count($pecah) - $i == 1) && ($pecah[$i] != 0)) {
                                        $string .= self::bilangRatusan($pecah[$i]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $string;
        }

        public static function countOffset($page, $limit) {
            return ($page - 1) * $limit;
        }

        public static function getBulan($id) {
            $month = array(1 => 'Januari',
                           2 => 'Februari',
                           3 => 'Maret',
                           4 => 'April',
                           5 => 'Mei',
                           6 => 'Juni',
                           7 => 'Juli',
                           8 => 'Agustus',
                           9 => 'September',
                           10 => 'Oktober',
                           11 => 'November',
                           12 => 'Desember',
            );

            return isset($month[(int) $id]) ? $month[(int) $id] : '';
        }

        public static function DateToIndo($date) {
            if (is_object($date) && !empty($date->waktu_sampai)) {
                $date = $date->waktu_sampai;
            }

            $BulanIndo = array(
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember",
            );

            $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
            $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
            $tgl = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring

            $bulan_kalender = isset($BulanIndo[(int) $bulan - 1]) ? $BulanIndo[(int) $bulan - 1] : $BulanIndo[0];
            $result = $tgl . " " . $bulan_kalender . " " . $tahun;

            // var_dump($result);exit();
            return ($result);
        }

        public static function warna($nilai) {
            // $nilai = '100704232000000.00';
            $var = substr($nilai, 0, -3);
            $val = self::rupiah_display($var);
            $trim = str_replace('Rp. ', '', $val);
            $explode = explode('.', $trim);
            $count = count($explode);
            // var_dump($count, $val);exit();
            if ($count == 4) {
                // var_dump(intval($explode[0]),intval($explode[1]));
                if (intval($explode[0]) > 2) {
                    $result = "milyar";

                    return $result;
                } else {
                    if (intval($explode[0]) == 1) {
                        $result = "juta";

                        return $result;
                    } else {
                        if (intval($explode[1]) <= 509) {
                            $result = "juta";

                            return $result;
                        } else {
                            $result = "milyar";

                            return $result;
                        }
                    }
                }
            } elseif ($count == 5) {
                $result = "milyar";
            } else {
                $result = "juta";
            }

            return $result;
        }

        public static function highlightWords($text, $words)
        {
            if (is_array($words)) {
                foreach ($words as $key => $value) {
                    $w[] = $value['keyword'];

                }
                $words = implode(" ", $w);
            }

            $words = explode(" ", $words);
            foreach ($words as $word) {
                /*             * * quote the text for regex ** */
                // $pattern = "/\\'\"\\\\/";
                // $replace = "";
                $word = preg_replace("/[^\w]/", "", $word);
                // $word = preg_replace($pattern, $replace, $word);
                /*             * * highlight the words ** */
                // $text = preg_replace("/\b($word)\b/i", '<span style="font-weight:bold;">\1</span>', $text);
                $text = str_ireplace($word, '<span style="font-weight:bold;">' . $word . '</span>', $text);
            }
            /*         * * return the text ** */
            return $text;
        }

        // Function to get the client IP address
        public static function get_client_ip() {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP')) {
                $ipaddress = getenv('HTTP_CLIENT_IP');
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
                } else {
                    if (getenv('HTTP_X_FORWARDED')) {
                        $ipaddress = getenv('HTTP_X_FORWARDED');
                    } else {
                        if (getenv('HTTP_FORWARDED_FOR')) {
                            $ipaddress = getenv('HTTP_FORWARDED_FOR');
                        } else {
                            if (getenv('HTTP_FORWARDED')) {
                                $ipaddress = getenv('HTTP_FORWARDED');
                            } else {
                                if (getenv('REMOTE_ADDR')) {
                                    $ipaddress = getenv('REMOTE_ADDR');
                                } else {
                                    $ipaddress = 'UNKNOWN';
                                }
                            }
                        }
                    }
                }
            }

            return $ipaddress;
        }

        /**
         *
         * @param integer $angka number
         *
         * @return string
         *
         * Contoh :
         * echo convertToRupiah(10000000); -> "Rp. 10.000.000"
         */
        public static function convertToRupiah($angka) {
            return 'Rp. ' . strrev(implode('.', str_split(strrev(strval($angka)), 3)));
        }

        /**
         *
         * @param string $rupiah
         *
         * @return integer
         *
         * Contoh :
         * echo convertToNumber("Rp. 10.000.123,00"); -> 10000123
         */
        public static function convertToNumber($rupiah) {
            $pattern = '/,.*|[^0-9]/';

            return doubleval(preg_replace($pattern, '', $rupiah));
        }

        /* Fungsi untuk memformat nilai */
        public static function formatAngka($n) {
            // Hilangkan format;
            $n = (0 + str_replace(",", "", $n));

            // Memastikan hanya angka saja?
            if (!is_numeric($n)) {
                return false;
            }

            // Saatnya difilter;
            if ($n >= 1000000000000 && $n < 1000000000000000) {
                return round(($n / 1000000000000), 2) . ' T';
            } elseif ($n >= 1000000000 && $n < 1000000000000) {
                $rd = round(($n / 1000000000), 2);
                if ($rd == 10000) {
                    $n = $rd / 10000;

                    return $n . ' T';
                } else {
                    return round(($n / 1000000000), 2) . ' M';
                }
            } elseif ($n >= 1000000 && $n < 1000000000) {
                $rd = round(($n / 1000000), 2);
                if ($rd == 1000) {
                    $n = $rd / 1000;

                    return $n . ' M';
                } else {
                    return round(($n / 1000000), 2) . ' Jt';
                }
            } elseif ($n >= 1000 && $n < 1000000) {
                $rd = round(($n / 1000), 2);
                if ($rd == 100) {
                    $n = $rd / 100;

                    return $n . ' Jt';
                } else {
                    return round(($n / 1000), 2) . ' Rb';
                }
            }

            return number_format($n);
        }

        public static function cleanStr($string) {
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

            return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        }

        public static function filterUrl($url) {
            $filter = filter_var($url, FILTER_SANITIZE_URL);

            return $filter;
        }

        public static function filterAngka($angka) {
            $filter = filter_var($angka, FILTER_SANITIZE_NUMBER_INT);

            return $filter;
        }

        public static function filterSpecialChars($value) {
            $filter = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);

            return $filter;
        }

        public static function filterString($value) {
            $filter = filter_var($value, FILTER_SANITIZE_MAGIC_QUOTES);

            return $filter;
        }

        public static function paginate_function($current_page, $total_pages, $url) {
            //$current_page = $page;
            //        if(!empty($url)){
            //            $url = Yii::app()->request->getBaseUrl() . $url;
            //        }

            $retUrl = Helper::urlHelper($url);

            $pagination = '';
            if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) {
                //Cek total halaman dan posisi halaman saat ini
                $pagination .= '<ul class="pagination pagination--front" id="yw0">';

                $links_kanan = $current_page + 10;
                $sebelumnya = $current_page - 1; //link sebelumnya
                $selanjutnya = $current_page + 1; //link selanjutnya
                $link_pertama = true; //var boolean untuk link pertama

                if ($current_page > 1) {
                    $previous_link = ($sebelumnya == 0) ? 1 : $sebelumnya;

                    $pagination .= '<li><a href="" data-page= "1" title="Halaman Awal"><i class="fa fa-angle-double-left"></i></a></li>'; //link pertama
                    $pagination .= '<li><a href="' . $url . $retUrl . 'page=' . $previous_link . '" data-page="' . $previous_link . '" title="Sebelumnya"><i class="fa fa-angle-left"></i></a></li>'; //link sebelumnya
                    for ($i = ($current_page - 2); $i < $current_page; $i++) {
                        // membuat link sebelah kiri
                        if ($i > 0) {
                            $pagination .= '<li><a href="' . $url . $retUrl . 'page=' . $i . '" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
                        }
                    }
                    $link_pertama = false; //set link pertama jadi false
                }

                if ($link_pertama) {
                    //jika halaman aktif saat ini adalah link pertama
                    $pagination .= '<li><a href="' . $url . $retUrl . 'page=' . $current_page . '" data-page="0">' . $current_page . '</a></li>';
                } elseif ($current_page == $total_pages) {
                    //jika link terakhir
                    $pagination .= '<li class="current"><a href="' . $url . $retUrl . 'page=' . $current_page . '" data-page="' . $current_page . '" id="page">' . $current_page . '</a></li>';
                } else {
                    // regular link yang aktif
                    $pagination .= '<li class="current"><a href="' . $url . $retUrl . 'page=' . $current_page . '" data-page="' . $current_page . '" id="page">' . $current_page . '</a></li>';
                }

                for ($i = $current_page + 1; $i < $links_kanan; $i++) {
                    //link sebelah kanan
                    if ($i <= $total_pages) {
                        $pagination .= '<li><a href="' . $url . $retUrl . 'page=' . $i . '" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
                    }
                }
                if ($current_page < $total_pages) {
                    $next_link = ($i > $total_pages) ? $total_pages : $i;
                    $pagination .= '<li><a href="' . $url . $retUrl . 'page=' . $next_link . '" data-page="' . $next_link . '" title="Selanjutnya"><i class="fa fa-angle-right"></i></a></li>'; //Link Selanjutnya
                    $pagination .= '<li class="arrow"><a href="' . $url . $retUrl . 'page=' . $total_pages . '" data-page="' . $total_pages . '" title="Halaman Akhir"><i class="fa fa-angle-double-right"></i></a></li>'; //link terakhir
                }

                $pagination .= '</ul>';
                // var_dump($item_per_page, $current_page, $total_records, $total_pages);exit;
            }

            return $pagination; //return pagination
        }

        public static function currency($angka) {
            if (is_numeric($angka)) {
                $angka = static::filterSpecialChars($angka);
                return number_format(static::filterAngka($angka), 0, ',', '.');
            }
            return "";
        }

        public static function escapeQuery($q) {
            $q = str_replace("'", "''", $q);
            $q = str_replace('\\', '\\\\', $q);
            $q = str_replace('_', '\\_', $q);
            $q = str_replace('%', '\\%', $q);

            return $q;
        }

        public static function getArrKey($arr, $key, $def = null) {
            return isset($arr[$key]) ? $arr[$key] : $def;
        }

        public static function searchInArray($needle, $haystack, $strict = false) {
            /*
             * find match value on multidimensional arrays
             * ex. $arr = array(array("sniper","barathrum"), array("drawranger","windrunner"))
             * echo searchInArray("sniper", $arr) ? true : false
             */
            foreach ($haystack as $key => $value) {
                if (($strict ? $value === $needle : $value == $needle) || (is_array($value) && self::searchInArray($needle, $value, $strict))) {
                    return true;
                }
            }

            return false;
        }

        public static function filterAlphaNumeric($str) {
            return preg_match("/^([1-zA-Z0-1@.\s]{1,255})$/", $str);
        }

        public static function filterNumeric($str) {
            if (is_numeric($str)) {
                return $str;
            }

            return "";

        }

        public static function urlHelper($url) {
            //$url = Yii::app()->request->requestUri;
            $url = explode('/', $url);
            if (isset($url[1]) && !empty($url[1])) {
                $op = strpos($url[1], "?");
                if (!$op) {
                    return "?";
                }
            }
            return "&";

        }

        public static function stripAttribute($text, $tag = array('class', 'id')) {
            return preg_replace_callback('@< (?<tag>\w+?) \s+ (?<attr>[^>]+) >@misx', function ($m) use ($tag) {
                if (preg_match_all('@(\s+|^)(?<key>\w+)=([\'"]?)(?<value>.*?)\3@mis', $m['attr'], $m2)) {
                    foreach ($m2['key'] as $x => $attr) {
                        if (in_array($attr, $tag)) {
                            //$m['tag'] .= " {$attr}={$m2['value'][$x]}";
                            $m['tag'] .= " {$attr}={$m2[3][$x]}{$m2['value'][$x]}{$m2[3][$x]}";
                        }
                    }
                }

                return '<' . $m['tag'] . '>';
            }, $text);
        }

        public static function cleanString($str) {
            $str = preg_replace('/\s+|&nbsp;/', ' ', $str);
            $str = self::convertOlToTable($str);
            $str = self::stripAttribute($str);

            return $str;
        }

        public static function selisihHari($date)
        {
            if (is_object($date) && !empty($date->waktu_sampai)) {
                $date = $date->waktu_sampai;
            }
            // $date = "2105-08-07 12:00:00";
            // $date = "2015-08-07 12:00:00";
            self::setTimeZone("Asia/Makassar");
            $waktu1 = new DateTime($date);
            $now = new DateTime('now');
            $d = $waktu1->format("Y-m-d H:i:s");
            // var_dump(strtotime($d),date("Y-m-d H:i:s"));
            // if (strtotime($date) < strtotime(date("Y-m-d H:i:s"))) {

            // }
            $difference = $now->diff($waktu1);
            $invert = $difference->invert;

            // function datediff($tgl1, $tgl2){
            // 	$tgl1 = strtotime($tgl1);
            // 	$tgl2 = strtotime($tgl2);
            // 	$diff_secs = abs($tgl1 - $tgl2);
            // 	$base_year = min(date("Y", $tgl1), date("Y", $tgl2));
            // 	$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
            // 	return array(
            // 		"years" => date("Y", $diff) - $base_year,
            // 		"months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
            // 		"months" => date("n", $diff) - 1,
            // 		"days_total" => floor($diff_secs / (3600 * 24)),
            // 		"days" => date("j", $diff) - 1,
            // 		"hours_total" => floor($diff_secs / 3600),
            // 		"hours" => date("G", $diff),
            // 		"minutes_total" => floor($diff_secs / 60),
            // 		"minutes" => (int) date("i", $diff),
            // 		"seconds_total" => $diff_secs,
            // 		"seconds" => (int) date("s", $diff)
            // 	);
            // }

            // $test = datediff($date,date("Y-m-d H:i:s"));

            // exit;


            $total_hari = "";
            $end = " hari, ";
            if ($invert === 0) {
                if ($difference->d > 0) {
                    $total_hari .= "Dalam " . $difference->d . $end;
                } else {
                    $total_hari .= "Hari Ini, ";
                }
            } else {
                if ($difference->d > 0) {
                    //$total_hari .= "Sekitar " . $difference->d . $end;
                    $total_hari .= "Selesai pada ";
                } else {
                    $total_hari .= "Hari Ini, ";
                }
            }

            $k = !empty($total_hari) ? $total_hari : "";

            return $k;
        }

        public static function timeGo($date) {
            $isValid = self::isDateTimeValid($date);
            if ($isValid) {
                $timestamp = strtotime($date);
                $difference = time() - $timestamp;
                $periods = array("detik", "menit", "jam", "hari", "minggu", "bulan", "tahun", "dekade");
                $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

                if ($difference > 0) {
                    // this was in the past time
                    $ending = "yang lalu";
                } else {
                    // this was in the future time
                    $difference = -$difference;
                    $ending = "lagi";
                }
                for ($j = 0; $difference >= $lengths[$j]; $j++) {
                    $difference /= $lengths[$j];
                }

                $difference = round($difference);

                // if ($difference != 1)
                //     $periods[$j].= "s";

                $text = "$difference $periods[$j] $ending";

                return $text;
            } else {
                return 'Waktu Tanggal harus dalam format "yyyy-mm-dd hh:mm:ss"';
            }
        }

        public static function isDateTimeValid($date) {
            if (Date('Y-m-d H:i:s', strtotime($date) == $date)) {
                return true;
            } else {
                return false;
            }
        }

        public static function encrypt_decrypt($action, $string) {
            $output = false;

            $encrypt_method = "AES-256-CBC";
            $secret_key = uniqueid();
            $secret_iv = uniqueid();

            // hash
            $key = hash('sha256', $secret_key);

            // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            if ($action == 'encrypt') {
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                $output = base64_encode($output);
            } else {
                if ($action == 'decrypt') {
                    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                }
            }

            return $output;
        }

        public static function paginate($group_links, $page_number, $total_pages, $page_url) {
            $first_exist = 0;
            $prev_exist = 0;
            $last_exist = 0;
            $group_links_min = ($group_links % 2 == 0) ? ($group_links / 2) - 1 : ($group_links - 1) / 2;
            $group_links_max = ($group_links % 2 == 0) ? $group_links_min + 1 : $group_links_min;

            $page_min = $page_number - $group_links_min;
            $page_max = $page_number + $group_links_max;

            $page_min = ($page_min < 1) ? 1 : $page_min;

            $page_max = ($page_max < ($page_min + $group_links - 1)) ? $page_min + $group_links - 1 : $page_max;

            if ($page_max > $total_pages) {
                $page_min = ($page_min > 1) ? $total_pages - $group_links + 1 : 1;
                $page_max = $total_pages;
            }

            $page_min = ($page_min < 1) ? 1 : $page_min;

            $pagination = "";
            $pagination .= '<ul class="pagination pagination--front" id="yw0">';

            if (($page_number > ($group_links - $group_links_min)) && ($total_pages > $group_links)) {
                $pagination .= '<li class="first"><a href="' . $page_url . '?page=1">&laquo;</a></li>';
                $first_exist = 1;
            }

            if ($page_number != 1) {
                if ($first_exist) {
                    $pagination .= '<li class="previous"><a href="' . $page_url . '?page=' . ($page_number - 1) . '">&lt; Pre</a></li>';
                } else {
                    $pagination .= '<li class="previous first"><a href="' . $page_url . '?page=' . ($page_number - 1) . '">&lt; Pre</a></li>';
                }
                $prev_exist = 1;
            }

            for ($i = $page_min; $i <= $page_max; $i++) {
                if ($i == $page_number) {
                    if ($prev_exist) {
                        $pagination .= '<li class="current">' . $i . '</li>';
                    } else {
                        $pagination .= '<li class="current first">' . $i . '</li>';
                    }
                } else {
                    $pagination .= '<li><a href="' . $page_url . '?page=' . $i . '">' . $i . '</a></li>';
                }
            }

            if ($page_number < $total_pages) {
                if (($page_number < ($total_pages - $group_links_max)) && ($total_pages > $group_links)) {
                    $pagination .= '<li><a href="' . $page_url . '?page=' . ($page_number + 1) . '">Next &gt;</a></li>';
                } else {
                    $pagination .= '<li class="last"><a href="' . $page_url . '?page=' . ($page_number + 1) . '">Next &gt;</a></li>';
                }
            }

            if (($page_number < ($total_pages - $group_links_max)) && ($total_pages > $group_links)) {
                $pagination .= '<li class="last"><a href="' . $page_url . '?page=' . $total_pages . '">&raquo;</a></li>';
            }
            $pagination .= '</ul>';

            return $pagination;
        }

        public static function pagination($total_data, $per_page = 10, $page = 1, $url = '?') {

            $total = $total_data;
            $adjacents = "2";

            $page = !empty($page) ? $page : 1;
            $start = ($page - 1) * $per_page;

            $prev = $page;
            $next = $page + 1;
            $lastpage = ceil($total / $per_page);
            //$lastpage = static::generatePaging($total_data);
            $lpm1 = $lastpage - 1;
            //$url = !empty($_GET['page'])? Helper::filterAlphaNumeric($_GET['page']) : 1;

            $pagination = "";
            //$url = preg_replace("#&d=.*&#", '&d=newvalue&', $_SERVER['REQUEST_URI']);
            if ($lastpage >= 1) {
                //var_dump($lastpage,$lpm1);exit;
                $pagination .= '<ul class="pagination pagination--front" id="yw0">';
                //$pagination .= "<li class='details'>Page $page of $lastpage</li>";
                if ($lastpage < 7 + ($adjacents * 2)) {
                    for ($counter = 0; $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<li><a href='{$url}&page=$counter' class='current'>$counter</a></li>";
                            // var_dump($counter,$page, $pagination, $lastpage+1);
                        } else {
                            $pagination .= "<li><a href='{$url}&page=$counter'>$counter</a></li>";
                        }
                    }
                } elseif ($lastpage > 5 + ($adjacents * 2)) {
                    if ($page < 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                            if ($counter == $page) {
                                $pagination .= "<li><a class='current'>$counter</a></li>";
                            } else {
                                $pagination .= "<li><a href='{$url}&page=$counter'>$counter</a></li>";
                            }
                        }
                        $pagination .= "<li class='dot'>...</li>";
                        $pagination .= "<li><a href='{$url}&page=$lpm1'>$lpm1</a></li>";
                        $pagination .= "<li><a href='{$url}&page=$lastpage'>$lastpage</a></li>";
                    } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                        $pagination .= "<li><a href='{$url}&page=1'>1</a></li>";
                        $pagination .= "<li><a href='{$url}&page=2'>2</a></li>";
                        $pagination .= "<li class='dot'>...</li>";
                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                            if ($counter == $page) {
                                $pagination .= "<li><a class='current'>$counter</a></li>";
                            } else {
                                $pagination .= "<li><a href='{$url}&page=$counter'>$counter</a></li>";
                            }
                        }
                        $pagination .= "<li class='dot'>..</li>";
                        $pagination .= "<li><a href='{$url}&page=$lpm1'>$lpm1</a></li>";
                        $pagination .= "<li><a href='{$url}&page=$lastpage'>$lastpage</a></li>";
                    } else {
                        $pagination .= "<li><a href='{$url}&page=1'>1</a></li>";
                        $pagination .= "<li><a href='{$url}&page=2'>2</a></li>";
                        $pagination .= "<li class='dot'>..</li>";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                            if ($counter == $page) {
                                $pagination .= "<li><a class='current'>$counter</a></li>";
                            } else {
                                $pagination .= "<li><a href='{$url}&page=$counter'>$counter</a></li>";
                            }
                        }
                    }
                }

                if ($page < $counter - 1) {
                    //var_dump($lastpage,$lpm1);exit;
                    $pagination .= "<li><a href='{$url}&page=$next'>Selanjutnya</a></li>";
                    $pagination .= "<li><a href='{$url}&page=$lastpage'>Sebelumnya</a></li>";
                } else {
                    $pagination .= "<li><a class='current'>Selanjutnya</a></li>";
                    $pagination .= "<li><a class='current'>Sebelumnya</a></li>";
                }
                $pagination .= "</ul>\n";
            }
            // exit;

            return $pagination;
        }

        public static function getBrowser() {
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
            $bname = 'Unknown';
            $platform = 'Unknown';
            $version = "";

            //First get the platform?
            if (preg_match('/linux/i', $u_agent)) {
                $platform = 'linux';
            } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                $platform = 'mac';
            } elseif (preg_match('/windows|win32/i', $u_agent)) {
                $platform = 'windows';
            }

            // Next get the name of the useragent yes seperately and for good reason
            if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            } elseif (preg_match('/Firefox/i', $u_agent)) {
                $bname = 'Mozilla Firefox';
                $ub = "Firefox";
            } elseif (preg_match('/Chrome/i', $u_agent)) {
                $bname = 'Google Chrome';
                $ub = "Chrome";
            } elseif (preg_match('/Safari/i', $u_agent)) {
                $bname = 'Apple Safari';
                $ub = "Safari";
            } elseif (preg_match('/Opera/i', $u_agent)) {
                $bname = 'Opera';
                $ub = "Opera";
            } elseif (preg_match('/Netscape/i', $u_agent)) {
                $bname = 'Netscape';
                $ub = "Netscape";
            }

            // finally get the correct version number
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
                // we have no matching number just continue
            }

            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                    $version = $matches['version'][0];
                } else {
                    $version = $matches['version'][1];
                }
            } else {
                $version = $matches['version'][0];
            }

            // check if we have a number
            if ($version == null || $version == "") {
                $version = "?";
            }

            return array(
                'userAgent' => $u_agent,
                'name' => $bname,
                'version' => $version,
                'platform' => $platform,
                'pattern' => $pattern,
            );
        }

        public static function get_tiny_url($url)
        {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
    }