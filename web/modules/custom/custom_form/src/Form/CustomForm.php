<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomForm extends FormBase{
    /**
     * {@inheritdoc }
     */
    public function getFormId(){
        return 'custom_form_form';
    }
    /**
 * {@inheritdoc }
 */
public function buildForm(array $form, FormStateInterface $form_state) {
    $form['field_direccion_de_recogida']=[
        '#type' => 'textfield',
        '#title' => $this->t('Dirección de recogida'),
    ];
    $form['field_latitud']=[
        '#type' => 'textfield',
        '#title' => $this->t('Latitud'),
    ];
    $form['field_longitud']=[
        '#type' => 'textfield',
        '#title' => $this->t('Longitud'),
    ];
    $form['field_nombres_y_apellidos']=[
        '#type' => 'textfield',
        '#title' => $this->t('Nombres y Apellidos'),
    ];
    $form['field_oficina']=[
        '#type' => 'textfield',
        '#title' => $this->t('Oficina'),
    ];
    $form['submit']=[
        '#type' => 'submit',
        '#value' => $this->t('Registrar'),
    ];
    return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $responseToken = $this->token();
        $response = $this->createRecogida(json_decode($responseToken)->csrf_token);
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

    public function createRecogida($token){

        global $base_url;
        $direccion_de_recogida = $_POST['field_direccion_de_recogida'];
        $latitud = $_POST['field_latitud'];
        $longitud = $_POST['field_longitud'];
        $nombres_y_apellidos = $_POST['field_nombres_y_apellidos'];
        $oficina = $_POST['field_oficina'];
        $titulo = $nombres_y_apellidos.' - '.date('Y-m-d H:i:s');
        $formato = $nombres_y_apellidos.'-'.date('Y-m-d-H-i-s');
        $basic = base64_encode('admin:123');

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
            "field_direccion_de_recogida": [
                {
                    "value": "'.$direccion_de_recogida.'"
                }
            ],
            "field_location": [
                {
                "lat": '.$latitud.',
                "lng": '.$longitud.',
                "value": "'.$latitud.','.$longitud.'"
                }
            ],
            "field_latitud": [
                {
                    "value": "'.$latitud.'"
                }
            ],
            "field_longitud": [
                {
                    "value": "'.$longitud.'"
                }
            ],
            "field_nombres_y_apellidos": [
                {
                    "value": "'.$nombres_y_apellidos.'"
                }
            ],
            "field_oficina": [
                {
                    "value": "'.$oficina.'"
                }
            ],
            "field_url_notificacion": [
                {
                    "uri": "'.$base_url.'/formulario-agenda",
                    "title": "Link para agendar",
                    "options": []
                }
            ],
            "type": [
                {
                    "target_id": "paquetes"
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
            return 'Ingresa al siguiente enlace <a href="'.$base_url.'/'.$formato.'" target="_blank">'.json_decode($response, true)['uuid'][0]['value'].'</a>';
        }
    }
}