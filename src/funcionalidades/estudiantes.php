<?php
require __DIR__ .'/../../vendor/autoload.php'; // Carga automática de dependencias


function obtenerClienteMongoDB()
{
    $cluster = "cluster0.6xkz1.mongodb.net/";
    $usuario = rawurlencode("santiago894");
    $pass = rawurlencode("P5wIGtXue8HvPvli");
    $cadenaConexion = sprintf("mongodb+srv://%s:%s@%s", $usuario, $pass, $cluster);
    $cliente = new MongoDB\Client($cadenaConexion);
    //"mongodb+srv://$usuario:$pass@cluster0.6xkz1.mongodb.net/"
    return $cliente;
}

function validarColeccion($nombreColeccion) {//si no existem documentos en esa coleccion devuelve false y si no devuelve el numero de documentos de esa coleccion
    $mongo = obtenerClienteMongoDB(); 
    $bd = $mongo->selectDatabase("ERP"); // Selecciona la base de datos
    $coleccionUsuarios = $bd->selectCollection($nombreColeccion); // Selecciona la colección
    $cursor = $coleccionUsuarios->countDocuments(); // Obtén el cursor para los documentos

    if ($cursor === 0) {
      echo "La colección está vacía.";
      return $cursor+1;
    } else {
      echo "La colección tiene al menos un documento.";
      return $cursor;
    }
}

function agregarEstudiante($nombreEstudiante,$apellidoAlumno, $dni,$telefono,$correo,$objeto_formaciones,$objeto_promociones,$bool_trabaja )
{
    $id=validarColeccion("estudiantes");
    $objeto_mongo_estudiante=[ 
        "_idAlumno"=>$id,
        "nombreAlumno" => $nombreEstudiante,
        "apellidoAlumno" => $apellidoAlumno,
        "dni"=> $dni,
        "telefono"=> $telefono,
        "correo"=> $correo,
        "formaciones"=> $objeto_formaciones,
        /*FORMACIONES[
            TCAE[
                promocion=>2023,
                asignaturas=>[asinatura1=>1.0, asinatura2=>10.0],
                titula=>bool;
            ]
        ]{ O INFORMATICA O GESTION},*/
        //año de promocion ejm 2023-2024,
        "trabajando"=> $bool_trabaja//Verdero o falso
  ];
    $mongo = obtenerClienteMongoDB();
    $bd = $mongo->selectDatabase("ERP");
    $coleccionUsuarios = $bd ->selectCollection("estudiantes");
    $coleccionUsuarios->insertOne($objeto_mongo_estudiante);
}

agregarEstudiante("John","Arenales","60333777B",111111111,"johndoe@gmail.com",[1=>["curso"=>"informatica","promocion"=>"2023-2024"],2=>["curso"=>"informatica","promocion"=>"2023-2024"]],$objeto_promociones,false);

validarColeccion("estudiantes");