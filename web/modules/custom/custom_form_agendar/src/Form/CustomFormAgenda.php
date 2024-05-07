<?php

namespace Drupal\custom_form_agendar\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomFormAgenda extends FormBase{
    /**
     * {@inheritdoc }
     */
    public function getFormId(){
        return 'custom_form_agenda';
    }
    /**
 * {@inheritdoc }
 */
public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit']=[
        '#type' => 'submit',
        '#value' => $this->t('Consultar agendamiento'),
    ];
    return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $responseToken = $this->token();
        $response = $this->createAgenda(json_decode($responseToken)->csrf_token);
        $this->messenger()->addStatus($this->t($response));
    }
    public function token(){
        $curl = curl_init();
        $basic = base64_encode('admin:123');

        global $base_url;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $base_url.'/user/login?_format=json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "name": "admin",
            "pass": "123"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic '.$basic.''
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function createAgenda($token){
    
        global $base_url;
        $basic = base64_encode('admin:123');
        $titulo = 'Agendamiento'.date('d-m-Y').date('-H-i-s');

        $conductor = $this->nodeChose($token,'Conductor');
        $arrLocCon = [];
        $arrDet = [];
        for($i = 0 ; $i < count($conductor->data) ; $i++){
            array_push($arrDet,$conductor->data[$i]->attributes);
            array_push($arrLocCon,$conductor->data[$i]->attributes->field_location_conductor[0]->value);
        }
        $paquetes = $this->nodeChose($token,'paquetes');
        $arrLocPaq = [];
        
        array_push($arrLocPaq,$paquetes->data[0]->attributes->field_location->value);
        
        $data =[
            'arrLocCon' => $arrLocCon,
            'arrLocPaq' => $arrLocPaq,
        ];
        $lanLonConductor = $this->lanLonConductor($data);
        
        $data =[
            'details' => $arrDet,
            'lanLonConductor' => $lanLonConductor,
        ];

        $detailsConductor = $this->detailsConductor($data);
        
        $nombres = $detailsConductor->field_nombres_apellidos[0]->value;
        $cedula = $detailsConductor->field_cedula[0]->value;
        $email = $detailsConductor->field_email[0];
        $empleado = $detailsConductor->field_empleado[0]->value;
        $tipo = $detailsConductor->field_tipo->value;
        $placa = $detailsConductor->field_placa[0]->value;
        $marca = $detailsConductor->field_marca[0]->value;

        ob_end_clean(); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=Descarga.csv');
        
        $data = '{
            "title": [
                {
                    "value": "'.$titulo.'"
                }
            ],
            "path": [
                {
                    "alias": "/'.$titulo.'"
                }
            ],
            "field_dia_recogida": [
                {
                    "value": "'.date('d-m-Y',strtotime(' + 1 days')).'"
                }
            ],
            "field_hora_recogida": [
                {
                    "value": "'.date('H:i:s').'"
                }
            ],
            "field_dia_de_registro": [
                {
                    "value": "'.date('d-m-Y').'"
                }
            ],
            "field_hora_de_registro": [
                {
                    "value": "'.date('H:i:s').'"
                }
            ],
            "field_ip_registro": [
                {
                    "value": "'.$_SERVER['REMOTE_ADDR'].'"
                }
            ],
            "field_nombres_emp": [
                {
                    "value": "'.$nombres.'"
                }
            ],
            "field_cedula_emp": [
                {
                    "value": "'.$cedula.'"
                }
            ],
            "field_email_emp": [
                {
                    "value": "'.$email.'"
                }
            ],
            "field_numero_emp": [
                {
                    "value": "'.$empleado.'"
                }
            ],
            "field_tipo_emp": [
                {
                    "value": "'.$tipo.'"
                }
            ],
            "field_placa_emp": [
                {
                    "value": "'.$placa.'"
                }
            ],
            "field_marca_emp": [
                {
                    "value": "'.$marca.'"
                }
            ],
            "field_descarga_csv": [
                {
                    "uri": "'.readfile("./Descarga.csv").'",
                    "title": "Link para descargar csv",
                    "options": []
                }
            ],
            "type": [
                {
                    "target_id": "agendamiento"
                }
            ]
            }';

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url.'/node?_format=json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'X-CSRF-Token: '.$token,
            'Content-Type: application/json',
            'Authorization: Basic '.$basic.''
        ),
    ));

    $response = curl_exec($curl);

        if(curl_getinfo($curl)['http_code'] == 400 ){
            return "NO fue exitosa,";
        }
        elseif(curl_getinfo($curl)['http_code'] == 201 ) {
            return 'Ingresa al siguiente enlace para ver el agendamiento <a href="'.$base_url.'/'.$titulo.'" target="_blank">'.json_decode($response, true)['uuid'][0]['value'].'</a>';
        }
    }
    public function detailsConductor($data){
        $array = $data['details'];
        $valor = $data['lanLonConductor'];

            for ($i = 0; $i < count($array); $i ++) {
                if($array[$i]->field_location_conductor[0]->value == $valor){
                    return $array[$i];
                }
            }
    }
    public function lanLonConductor($data){

        $array = $data['arrLocCon'];
        $valor = $data['arrLocPaq'][0];

           // Dividir la variable de entrada en latitud y longitud
            list($latitud, $longitud) = explode(', ', $valor);
            $latitud = (float) $latitud;
            $longitud = (float) $longitud;
            
            // Inicializar la distancia mínima y el índice correspondiente
            $distancia_minima = PHP_FLOAT_MAX;
            $indice_cercano = null;
            
            // Iterar sobre cada elemento del array
            foreach ($array as $indice => $elemento) {
                // Dividir el elemento del array en latitud y longitud
                list($lat_elemento, $long_elemento) = explode(', ', $elemento);
                $lat_elemento = (float) $lat_elemento;
                $long_elemento = (float) $long_elemento;
                
                // Calcular la distancia entre el elemento del array y la variable de entrada
                $distancia = sqrt(pow($lat_elemento - $latitud, 2) + pow($long_elemento - $longitud, 2));
                
                // Actualizar la distancia mínima y el índice correspondiente si encontramos una distancia menor
                if ($distancia < $distancia_minima) {
                    $distancia_minima = $distancia;
                    $indice_cercano = $indice;
                }
            }
            // Devolver el elemento del array más cercano
            return $array[$indice_cercano];
    }
        public function nodeChose($token, $type){

            $curl = curl_init();

            global $base_url;
            $basic = base64_encode('admin:123');
    
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $base_url.'/jsonapi/node/'.$type,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            'X-CSRF-Token: '.$token,
            'Content-Type: application/json',
            'Authorization: Basic '.$basic.''
            ),
            ));
            $response = curl_exec($curl);           
            
            if(curl_getinfo($curl)['http_code'] == 404 ){
                curl_close($curl);
                return 0;
            }
            elseif(curl_getinfo($curl)['http_code'] == 200 ) {
                
            curl_close($curl);
                return json_decode($response);
            }
        }
}