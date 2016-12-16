<?php
// Routes

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
 * hacer twin
 */

/*
class Order extends Illuminate\Database\Eloquent\Model {

    protected $fillable = ['title'];
    public $timestamps = false;
}
*/

$app->post('/insert/pic',function(){
    $json = file_get_contents('php://input');
    $pic = json_decode($json,true);
    $conexion = mysqli_connect('localhost','root','','pictwin');
    $consulta = "INSERT INTO Pic (deviceId,date,latitud,longitud,positivo,negativo,warning,pic) VALUES 
('".$pic['deviceId']."','".$pic['date']."','".$pic['latitud']."','".$pic['longitud']."',0,0,0,1,'".$pic['pic']."')";
    $conexion->query($consulta);
    mysqli_close($conexion);

});

$app->post('/get/pic', function (){
    $data = file_get_contents('php://input');
    $pic = json_decode($data, true);

    $conexion = mysqli_connect('localhost', 'root', '', 'pictwin');
      $consulta = " SELECT
  Pic.*
FROM
  Twin, Pic
WHERE
  Twin.remote_pic_id = Pic.id
GROUP BY Pic.id
HAVING count(Pic.id) = (
    SELECT count(Pic.id) AS count
  FROM
    Twin, Pic
  WHERE
    Twin.remote_pic_id = Pic.id
  GROUP BY Pic.id
  ORDER BY count
  LIMIT 1);";

    $resultado = $conexion->query($consulta);

    /*
 * hacer update de el cont
 */
/*
 * hacer el twin, me falta el id de la primera
 */
    $arreglo = $resultado->fetch_assoc();

    mysqli_close(conexion);
    $json = json_encode($arreglo);
    return $json;
});

