<?php

namespace Drupal\custom_form_conductor\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomFormConductor extends FormBase{
    /**
     * {@inheritdoc }
     */
    public function getFormId(){
        return 'custom_form_conductor';
    }
/**
 * {@inheritdoc }
 */
public function buildForm(array $form, FormStateInterface $form_state) {
    $form['field_nombres_apellidos']=[
        '#type' => 'textfield',
        '#title' => $this->t('Nombres y apellidos'),
    ];
    $form['field_email']=[
        '#type' => 'textfield',
        '#title' => $this->t('Email'),
    ];
    $form['field_cedula']=[
        '#type' => 'textfield',
        '#title' => $this->t('Numero de Cedula'),
    ];
    $form['field_empleado']=[
        '#type' => 'textfield',
        '#title' => $this->t('Numero Empleado'),
    ];    
    $form['field_marca']=[
        '#type' => 'textfield',
        '#title' => $this->t('Marca del carro'),
    ];
    $form['field_tipo']=[
        '#type' => 'select',
        '#options' => array(
            'camioneta' => 'Camioneta',
            'furgon' => 'Furgon',
            'sedan' => 'Sedan',
        ),
        '#title' => $this->t('Tipo de carro'),
    ];
    $form['field_placa']=[
        '#type' => 'textfield',
        '#title' => $this->t('Placa'),
    ];
    $form['field_latitud']=[
        '#type' => 'textfield',
        '#title' => $this->t('Latitud'),
    ];
    $form['field_longitud']=[
        '#type' => 'textfield',
        '#title' => $this->t('Longitud'),
    ];
    $form['submit']=[
        '#type' => 'submit',
        '#value' => $this->t('Crear'),
    ];
    return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $responseToken = $this->token();
        $this->registerUser(json_decode($responseToken)->csrf_token);
        $response = $this->createConductor(json_decode($responseToken)->csrf_token);
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
    public function registerUser($token){
        $curl = curl_init();
        
        $email = $_POST['field_email'];
        $nickname = explode('@', $email);
        
        global $base_url;
        $basic = base64_encode('admin:123');
        $data =
            '{
            "name":{"value":"'.$nickname[0].'"},
            "mail":{"value":"'.$email.'"},
            "pass":{"value":"abc"},
            "status":{"value":"1"}
            }';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $base_url.'/entity/user?_format=json',
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
            'Authorization: Basic '.$basic
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
        
    }
    public function createConductor($token){

        global $base_url;
        $marca = $_POST['field_marca'];
        $nombres_apellidos = $_POST['field_nombres_apellidos'];
        $email = $_POST['field_email'];
        $cedula = $_POST['field_cedula'];
        $empleado = $_POST['field_empleado'];
        $tipo = $_POST['field_tipo'];
        $placa = $_POST['field_placa'];
        $titulo = 'Conductor - '.$nombres_apellidos.' - '.date('Y-m-d H:i:s');
        $formato = 'Conductor-'.$nombres_apellidos.'-'.date('Y-m-d-H-i-s');
        $basic = base64_encode('admin:123');        
        $latitud = $_POST['field_latitud'];
        $longitud = $_POST['field_longitud'];

        $data = '{
            "title": [
                {
                    "value": "'.$titulo.'"
                }
            ],
            "path": [
                {
                    "alias": "/'.$formato.'"
                }
            ],
            "field_marca": [
                {
                    "value": "'.$marca.'"
                }
            ],
            "field_nombres_apellidos": [
                {
                    "value": "'.$nombres_apellidos.'"
                }
            ],
            "field_email": [
                {
                    "value": "'.$email.'"
                }
            ],
            "field_cedula": [
                {
                    "value": "'.$cedula.'"
                }
            ],
            "field_empleado": [
                {
                    "value": "'.$empleado.'"
                }
            ],
            "field_placa": [
                {
                    "value": "'.$placa.'"
                }
            ],
            "field_tipo": [
                {
                    "value": "'.$tipo.'"
                }
            ],
            "field_location_conductor": [
                {
                "lat": '.$latitud.',
                "lng": '.$longitud.',
                "value": "'.$latitud.','.$longitud.'"
                }
            ],
            "field_latitud_conductor": [
                {
                "value": "'.$latitud.'"
                }
            ],
            "field_longitud_conductor": [
                {
                "value": "'.$longitud.'"
                }
            ],
            "type": [
                {
                    "target_id": "conductor"
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
            return 'Ingresa al siguiente enlace para ver el Conductor <a href="'.$base_url.'/'.$formato.'" target="_blank">'.json_decode($response, true)['uuid'][0]['value'].'</a>';
        }
    }
}