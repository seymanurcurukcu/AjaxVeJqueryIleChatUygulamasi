<?php 
$vt=new PDO("mysql:host=localhost;dbname=kisiler;charset:utf8","root");

class chat
{
    public $arkaplan,$yazirenk;
    function kisigetir($vt){
        $uye=$vt->prepare("select * from kisiler");
        $uye->execute();
        while ($geldi=$uye->fetch(PDO::FETCH_ASSOC)) {
           if ($geldi["durum"]==1) {
               echo'<span class="text-success">'.$geldi["ad"].' -Online </span><br>';
           }
           else {
             echo'<span class="text-danger">'.$geldi["ad"].' -Ofline </span><br>';
           }
        }
    }   
    
    function girisKontrol($vt,$kulad,$sifre){
        $sor=$vt->prepare("select * from kisiler where ad='$kulad' and sifre='$sifre'");
        $sor->execute();
        $veri=$sor->fetch(PDO::FETCH_ASSOC);
        if ($sor->rowCount()==0) {
            echo '<div class="alert alert-danger">BİLGİLER HATALI</div>';
            header("refresh:2,url=index.php");
        }
        else {
            $sor2=$vt->prepare("update kisiler set durum=1 where ad='$kulad'");
            $sor2->execute();
            echo '<div class="alert alert-success">Giriş yapılıyor</div>';
            header("refresh:2,url=chat.php");
            setcookie("kisiad",$kulad);
        }
    }
    function oturumKontrol($vt,$durum=false){
        if (isset($_COOKIE["kisiad"])) {
           $kisiad=$_COOKIE["kisiad"];
           $sor=$vt->prepare("select * from kisiler where ad='$kisiad'");
           $sor->execute();
           $veri=$sor->fetch(PDO::FETCH_ASSOC);
           if ($sor->rowCount()==0) {
               header("Location:index.php");
            }
            else {
                if ($durum==true) {
                    header("Location:chat.php");
                }
                
            }
        }
        else {
            if ($durum==false) {
                header("Location:index.php");
            }
            
        }

    }

    function renklerebak($vt){
        $kisiad=$_COOKIE["kisiad"];
        $sor=$vt->prepare("select * from kisiler where ad='$kisiad'");
        $sor->execute();
        $veri=$sor->fetch(PDO::FETCH_ASSOC);
        $this->arkaplan=$veri["arkarenk"];
        $this->yazirenk=$veri["yazirenk"];
    }
   

}
@$chat=$_GET["chat"];
switch ($chat) {
    case 'oku':
      
        $dosya=fopen("konusmalar.txt", "r");	
        while (!feof($dosya)):
        $satir=fgets($dosya);
        print($satir);	
        endwhile;
        fclose($dosya); 
        break;
        
    case 'ekle':
        $kisiad=$_COOKIE["kisiad"]; 	
        $sor2=$vt->prepare("select * from kisiler where ad='$kisiad'");
       $sor2->execute();
       $sonuc=$sor2->fetch(PDO::FETCH_ASSOC);    
       $mesaj=htmlspecialchars(strip_tags($_POST["mesaj"]));    
       fwrite(fopen("konusmalar.txt","a"), '<span class="pb-5" style="color:#'.$sonuc["yazirenk"].'"><kbd style="background-color:#'.$sonuc["arkarenk"].'">'.$kisiad.'</kbd>'.$mesaj.'</span><br>');
        break;

    case 'cikis':
        $kisiad=$_COOKIE["kisiad"];
        $sor2=$vt->prepare("update kisiler set durum=0 where ad='$kisiad'");
        $sor2->execute();
        setcookie("kisiad",$kulad,time()-1);
        header("Location:index.php");
        break;
    case 'sohbetayar':
        if ($_POST) {
            $istek=$_POST["secenek"];
            if ($istek=="temizle") {
               unlink("konusmalar.txt");
               touch("konusmalar.txt");
               echo '<div class="alert alert-success mt-3">Sohbet Temizlendi</div>';
            }
            elseif ($istek=="kaydet") {
                copy("konusmalar.txt","kaydedilenler/".date("d.m.y")."-konusma.txt");
                echo '<div class="alert alert-success mt-3">Sohbet Kaydedildi</div>';
            }
        }
      
        break;
    case 'arkarenk':
        if ($_POST) {
           $gelenrenk=$_POST["arkaplankod"];
           $kisiad=$_COOKIE["kisiad"];
           $sor=$vt->prepare("update kisiler set arkarenk='$gelenrenk' where ad='$kisiad'");
           $sor->execute();
           echo '<div class="alert alert-success mt-3">Arka plan renk değiştirildi</div>';
        }
       
        break;
    case 'yazirenk':
       
        if ($_POST) {
            $gelenrenk=$_POST["yazirenkkod"];
            $kisiad=$_COOKIE["kisiad"];
            $sor=$vt->prepare("update kisiler set yazirenk='$gelenrenk' where ad='$kisiad'");
            $sor->execute();
            echo '<div class="alert alert-success mt-3">Yazı rengi değiştirildi</div>';
         }
        break;
    case 'ortak':
        if ($_GET["uyead"]!="") {
            fwrite(fopen("kisiler.txt","a"),'<span class="pb-5">'.$_GET["uyead"].'Yazıyor...</span><br>');
        }
        else if ($_GET["temizle"]!="") {
           $dosya="kisiler.txt";
           $ac=fopen($dosya,"r");
           $oku=fread($ac,filesize($dosya));
           $str=str_replace('<span class="pb-5">'.$_GET["temizle"].'Yazıyor...</span><br>',"",$oku);
           $yaz="kisiler.txt";
           $yazdir=fopen($yaz,"w");
           fwrite($yazdir,$str);
           fclose($yazdir);
        }
        break;
    case 'dosyaoku':
        $dosya=fopen("kisiler.txt", "r");	
        while (!feof($dosya)):
        $satir=fgets($dosya);
        print($satir);	
        endwhile;
        fclose($dosya); 
        break;
    default:
        # code...
        break;
}


?>