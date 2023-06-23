<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckIbanController extends AbstractController
{
    /**
     * @Route("/check/iban", name="app_check_iban")
     */
    public function verificador(): Response
    {
        // Valor a validar
        $value = 'ES4630048892115357624914';
        //ES6520388565649243971983
        //ES2331905938184182482381
        //ES3830041121893353893756
        //ES5201286619264338842666
        //ES3400811785311628498698
        //ES8204872266365621671132
        //ES0920955743412735282868
        //ES9621002667772917922717
        //ES3930048983232977378127
        //ES2220802661045125766342
        //ES3401284971747896228191
        //ES4000811519331235975199
        //SK0791444421148986799394  ESLOVENIA

        // Llamada a la función de validación del IBAN
        $resultadoIban = $this->checkIBAN($value);


        // Crear el contenido de la respuesta
        $contenidoRespuesta = "IBAN: " . $resultadoIban;

        // Crear la respuesta HTTP con el contenido deseado
        $response = new Response($contenidoRespuesta);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }


    // -------------------- Verifica que el IBAN sea correcto ------------------------------------------- //

    public function checkIBAN($iban)
    {
        if (strlen($iban) == 24)

            //Convierte en mayusculas        
            $iban = strtolower(str_replace(' ', '', $iban));
        $Countries = array('al' => 28, 'ad' => 24, 'at' => 20, 'az' => 28, 'bh' => 22, 'be' => 16, 'ba' => 20, 'br' => 29, 'bg' => 22, 'cr' => 21, 'hr' => 21, 'cy' => 28, 'cz' => 24, 'dk' => 18, 'do' => 28, 'ee' => 20, 'fo' => 18, 'fi' => 18, 'fr' => 27, 'ge' => 22, 'de' => 22, 'gi' => 23, 'gr' => 27, 'gl' => 18, 'gt' => 28, 'hu' => 28, 'is' => 26, 'ie' => 22, 'il' => 23, 'it' => 27, 'jo' => 30, 'kz' => 20, 'kw' => 30, 'lv' => 21, 'lb' => 28, 'li' => 21, 'lt' => 20, 'lu' => 20, 'mk' => 19, 'mt' => 31, 'mr' => 27, 'mu' => 30, 'mc' => 27, 'md' => 24, 'me' => 22, 'nl' => 18, 'no' => 15, 'pk' => 24, 'ps' => 29, 'pl' => 28, 'pt' => 25, 'qa' => 29, 'ro' => 24, 'sm' => 27, 'sa' => 24, 'rs' => 22, 'sk' => 24, 'si' => 19, 'es' => 24, 'se' => 24, 'ch' => 21, 'tn' => 24, 'tr' => 26, 'ae' => 23, 'gb' => 22, 'vg' => 24);
        // Definir array con el valor de cada letra
        $Chars = array('a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22, 'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35);

        if (array_key_exists(substr($iban, 0, 2), $Countries) && strlen($iban) == $Countries[substr($iban, 0, 2)]) {

            $MovedChar = substr($iban, 4) . substr($iban, 0, 4);
            $MovedCharArray = str_split($MovedChar);
            $NewString = "";

            foreach ($MovedCharArray as $key => $value) {
                if (!is_numeric($MovedCharArray[$key])) {
                    if (!isset($Chars[$MovedCharArray[$key]])) return false;
                    $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                }
                $NewString .= $MovedCharArray[$key];
            }

            //Es para evitar desbordamientos si tratamos como enteros los numeros, por eso se trabaja con cadenas y no se usa % sino bcmod
            if (bcmod($NewString, '97') == 1) {
                return "El Iban ingresado es correcto";
            }
        }
        return "El Iban ingresado es Incorrecto, por favor verifique el valor ingresado";
    }
}
