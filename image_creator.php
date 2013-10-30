<?
include("SimpleImage.inc.php");

$dh = opendir("uploads");
while($file = readdir($dh))
{
    $width = 50;
    $height = 50;
    //if (!is_file($file)) continue;
    if (is_dir($file)) continue;
    if (file_exists("uploads/small/$file"))
    {
        echo "El archivo $file ya tiene version chica.\n";
    }
    else
    {
        $img = new SimpleImage();
        $img->load("uploads/$file");
        $img->resize($width, $height);
        $img->save("uploads/small/$file");
        echo "Creada version chica del archivo $file.\n";
    }
}
closedir($dh);
?>
