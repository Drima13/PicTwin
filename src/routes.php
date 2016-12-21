<?php
// Routes

// nombre servidor
$app->get('/[{name}]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    return $this->renderer->render($response, 'index.phtml', $args);
});
/*
 * clase que guarda imagenes y sus respectivos atributos
 */
class Pic extends Illuminate\Database\Eloquent\Model {

    protected $table = 'Pic';
    //relleno massivo
    protected $fillable = ['deviceId','date','latitud','longitud','positivo','negativo','warning'];
    //eliminar tablas created_at updated_at
    public $timestamps = false;
}
/*
 * twin con los id de local y remota
 */
class Twin extends Illuminate\Database\Eloquent\Model {

    protected $table = 'Twin';
    //relleno massivo
    protected $fillable = ['idLocal','idRemota'];
    //eliminar tablas created_at updated_at
    public $timestamps = false;
}
/*
class Order extends Illuminate\Database\Eloquent\Model {

    protected $fillable = ['title'];
    public $timestamps = false;
}
*/
//insertar pic
$app->post('/insert/pic',function(){
    $json = file_get_contents('php://input');
    $pic = json_decode($json,true);
    $conexion = mysqli_connect('localhost','root','','pictwin');

    $consulta = "INSERT INTO Pic (deviceId,date,latitud,longitud,positivo,negativo,warning,pic) VALUES  
                  ('".$pic['deviceId']."','".$pic['date']."','".$pic['latitud']."','".$pic['longitud']."',
                  0,0,0,'".$pic['pic']."')";

    $conexion->query($consulta);

    $consulta = "SELECT id FROM Pic WHERE pic = '".$pic['pic']."'";
    $resultado =$conexion->query($consulta);
    $arreglo = $resultado->fetch_assoc();
    mysqli_close(conexion);
    $json = json_encode($arreglo);
    return $json;


});

$app->post('/get/pic', function (){
    $json = file_get_contents('php://input');
    $pic = json_decode($json,true);
    $conexion = mysqli_connect('localhost', 'root', '', 'pictwin');
      $consulta = " SELECT Pic.*
                    FROM Twin, Pic
                    WHERE Twin.idRemota = Pic.id
                    GROUP BY Pic.id
                    HAVING count(Pic.id) = (
                    SELECT count(Pic.id) AS count
                    FROM Twin, Pic
                    WHERE Twin.idRemota = Pic.id
                    GROUP BY Pic.id
                    ORDER BY count
                    LIMIT 1)";

    $resultado = $conexion->query($consulta);
    $arreglo = $resultado->fetch_assoc();
    $consulta = "INSERT INTO Twin (idLocal,idRemota) VALUES ('".$pic['dbId']."', '".$arreglo['id']."')";
    $conexion->query($consulta);
    mysqli_close(conexion);
    $json = json_encode($arreglo);
    return $json;
});



$app->post('/insert/positivo',function(){
    $json = file_get_contents('php://input');
    $pic = json_decode($json,true);
    $conexion = mysqli_connect('localhost','root','','pictwin');

    $consulta = "UPDATE Pic SET positive= positive + 1 WHERE id = '".$pic['dbid']."'";

    $conexion->query($consulta);
    mysqli_close($conexion);


});

$app->post('/insert/negativo',function(){
    $json = file_get_contents('php://input');
    $pic = json_decode($json,true);
    $conexion = mysqli_connect('localhost','root','','pictwin');

    $consulta = "UPDATE Pic SET negativo = negativo + 1 WHERE id = '".$pic['dbid']."'";

    $conexion->query($consulta);
    mysqli_close($conexion);


});

$app->post('/insert/warning',function(){
    $json = file_get_contents('php://input');
    $pic = json_decode($json,true);
    $conexion = mysqli_connect('localhost','root','','pictwin');

    $consulta = "UPDATE Pic SET warning = warning + 1 WHERE id = '".$pic['dbid']."'";

    $conexion->query($consulta);
    mysqli_close($conexion);


});