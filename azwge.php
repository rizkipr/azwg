<?php
error_reporting(0);
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890', ceil($length/strlen($x)) )),1,$length);
}
function getStr($string,$start,$end){
	$str = explode($start,$string);
	$str = explode($end,$str[1]);
	return $str[0];
}
function save($data,$name){
        $myfile = fopen($name, "a+") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
        if($myfile){
                return true;
        }else{
                return false;
        }
}
function ambiltoken() {
$ch = curl_init("http://www.md5decrypt.org/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$exec = curl_exec($ch);
preg_match_all("/jscheck='(.*?)'/", $exec, $match);
return $match[1][1];
}
function check($token,$hash,$name, $position) {
if($position !== "3") {
$hash = explode("|", $hash);
} else {
$hash = $hash;
}
if($position == "1") {
$data = "jscheck=".$token."&value=".base64_encode($hash[1])."&operation=MD5D";
} else if($position == "2") {
$data = "jscheck=".$token."&value=".base64_encode($hash[0])."&operation=MD5D";
} else if($position == "3") {
$data = "jscheck=".$token."&value=".base64_encode($hash)."&operation=MD5D";
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.md5decrypt.org/index/process');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($ch);
$json = json_decode($result, true);
if($json['error'] == "" && $position == "1") {
$hasil = "$hash[0]|".$json['body']."\n";
$ret = "".$json['body']." => Found\n";
save($hasil, $name);
return $ret;
} else if($json['error'] == "" && $position == "2") {
$hasil = "$hash[1]|".$json['body']."\n";
$ret = "".$json['body']." => Found\n";
save($hasil, $name);
return $ret;
} else if($json['error'] == "" && $position == "3") {
$hasil = "".$json['body']."\n";
$ret = "".$json['body']." => Found\n";
save($hasil, $name);
return $ret;
} else if($json['found'] == "" && $json['body'] == "" && $position == "1") {
$hasil = "$hash[0]|$hash[1]\n";
$name = str_replace('.txt', '-NotFound.txt', $name);
$ret = "".$hash[1]." => Not Found\n";
save($hasil, $name);
return $ret;
} else if($json['found'] == "" && $json['body'] == "" && $position == "2") {
$hasil = "$hash[1]|$hash[0]\n";
$name = str_replace('.txt', '-NotFound.txt', $name);
$ret = "".$hash[0]." => Not Found\n";
save($hasil, $name);
return $ret;
} else if($json['found'] == "" && $json['body'] == "" && $position == "3") {
$hasil = "$hash\n";
$name = str_replace('.txt', '-NotFound.txt', $name);
$ret = "".$hash." => Not Found\n";
save($hasil, $name);
return $ret;
} else {
$hasil = "$hash => UNKNOWN\n";
$name = str_replace('.txt', '-Unknown.txt', $name);
save($hasil, $name);
return $hasil;
}
}
 function jumlah($msg){
            echo "[IDLIVE - MD5 Decrypt] ".$msg;
            $answer =  rtrim( fgets( STDIN ));
            return $answer;
    }
echo "\n";
echo "$#############################################\n";
echo "##############################################\n";
echo "###    ###       ###########   ###############\n";
echo "###    ###        ##########   ###############\n";
echo "###    ###         #########   ###############\n";
echo "###    ###   ####   ########   ###############\n";
echo "###    ###    ##     #######   ###############\n";
echo "###    ###   ####    #######   ###############\n";
echo "###    ###          ########          ########\n";
echo "###    ###         #########          ########\n";
echo "##############################################\n";
echo "##############################################\n";
echo "==============================================\n";
echo "         IDLIVE [MRCK222] - MD5 Decrypter     \n";
echo "		      Version : 0.12.0              \n";
echo "==============================================\n\n";
$cek = jumlah("File Md5.txt : ");
$position = jumlah("1 For Email|MD5, 2 For MD5:Email, 3 for Md5 : ");
$savefile = jumlah("Savefile.txt : ");
$no = 1;
$file = file_get_contents($cek);
if($file == "") {
echo "File MD5 Not Found !\n";
exit();
}
$ex = explode("\n", $file);
$token = ambiltoken();
for($i=0;$i<count($ex);$i++) {
if(strpos($ex[$i], ":") !== FALSE) {
if($i % 10 == 0 && $i > 0) {
echo "Mengambil Token Baru........";
$token = ambiltoken();
echo "    BERHASIL!\n New Token : $token\n";
}
$hash = str_replace(":", "|", $ex[$i]);
} else {
$hash = $ex[$i];
}
$hes = explode("|", $hash);
if($position == "1") {
echo "[$no/".count($ex)."] $hes[0]|";
echo check($token, $hash, $savefile, $position);
} else if($position == "2") {
$hash = "$hes[1]|$hes[0]";
echo "[$no/".count($ex)."] ";
echo "$hes[1]|";
echo check($token, $hash, $savefile, $position);
} else if($position == "3") {
echo "[$no/".count($ex)."] ";
echo check($token, $hash, $savefile, $position);
}
$no++;
}
