<?php


class sound{

	function MakeSoundEx($stringtomakesoundexof)
{
    $temp_Name = $stringtomakesoundexof;
    $SoundKey1 = "BPFV";
    $SoundKey2 = "CSKGJQXZ";
    $SoundKey3 = "DT";
    $SoundKey4 = "L";
    $SoundKey5 = "MN";
    $SoundKey6 = "R";
    $SoundKey7 = "AEHIOUWY";

        $temp_Name = strtoupper($temp_Name);
    $temp_Last = "";
    $temp_Soundex = substr($temp_Name, 0, 1);

    $n = 1;
    for ($i = 0; $i < strlen($SoundKey1); $i++)
            if ($temp_Soundex == substr($SoundKey1, $i - 1, 1))
            	$temp_Last = "1";
    for ($i = 0; $i < strlen($SoundKey2); $i++)
            if ($temp_Soundex == substr($SoundKey2, $i - 1, 1))
            	$temp_Last = "2";
    for ($i = 0; $i < strlen($SoundKey3); $i++)
            if ($temp_Soundex == substr($SoundKey3, $i - 1, 1))
            	$temp_Last = "3";
    for ($i = 0; $i < strlen($SoundKey4); $i++)
            if ($temp_Soundex == substr($SoundKey4, $i - 1, 1))
            	$temp_Last = "4";
    for ($i = 0; $i < strlen($SoundKey5); $i++)
            if ($temp_Soundex == substr($SoundKey5, $i - 1, 1))
            	$temp_Last = "5";
    for ($i = 0; $i < strlen($SoundKey6); $i++)
            if ($temp_Soundex == substr($SoundKey6, $i - 1, 1))
            	$temp_Last = "6";
    for ($i = 0; $i < strlen($SoundKey6); $i++)
            if ($temp_Soundex == substr($SoundKey6, $i - 1, 1))
            	$temp_Last = "";

    for ($n = 1; $n < strlen($temp_Name); $n++)
    {
        if (strlen($temp_Soundex) < 4)
        {
            for ($i = 0; $i < strlen($SoundKey1); $i++)
                if (substr($temp_Name, $n - 1, 1) == substr($SoundKey1, $i - 1, 1) && $temp_Last != "1")
                {
                    $temp_Soundex = $temp_Soundex."1";
                    $temp_Last = "1";
                }
            for ($i = 0; $i < strlen($SoundKey2); $i++)
                if (substr($temp_Name, $n - 1, 1) == substr($SoundKey2, $i - 1, 1) && $temp_Last != "2")
                {
                    $temp_Soundex = $temp_Soundex."2";
                    $temp_Last = "2";
                }
            for ($i = 0; $i < strlen($SoundKey3); $i++)
                if (substr($temp_Name, $n - 1, 1) == substr($SoundKey3, $i - 1, 1) && $temp_Last != "3")
                {
                    $temp_Soundex = $temp_Soundex."3";
                    $temp_Last = "3";
                }
            for ($i = 0; $i < strlen($SoundKey4); $i++)
                if (substr($temp_Name, $n - 1, 1) == substr($SoundKey4, $i - 1, 1) && $temp_Last != "4")
                {
                    $temp_Soundex = $temp_Soundex."4";
                    $temp_Last = "4";
                }
            for ($i = 0; $i < strlen($SoundKey5); $i++)
                if (substr($temp_Name, $n - 1, 1) == substr($SoundKey5, $i - 1, 1) && $temp_Last != "5")
                {
                    $temp_Soundex = $temp_Soundex."5";
                    $temp_Last = "5";
                }
            for ($i = 0; $i < strlen($SoundKey6); $i++)
                if (substr($temp_Name, $n - 1, 1) == substr($SoundKey6, $i - 1, 1) && $temp_Last != "6")
                {
                    $temp_Soundex = $temp_Soundex."6";
                    $temp_Last = "6";
                }
            for ($i = 0; $i < strlen($SoundKey7); $i++)
                if (substr($temp_Name, $n - 1, 1) == substr($SoundKey7, $i - 1, 1))
                    $temp_Last = "";
        }
    }

    while (strlen($temp_Soundex) < 4)
        $temp_Soundex = $temp_Soundex."0";

    return $temp_Soundex;
}
};

$soundex = new sound();
//echo $soundex->MakeSoundEx($_GET['str']);

?>