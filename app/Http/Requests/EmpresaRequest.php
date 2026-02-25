<?php
// app/Http/Requests/EmpresaRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            'razon_social'  => 'required|string|max:200',
            'ruc'           => 'nullable|string|digits:11',
            'direccion'     => 'nullable|string|max:300',
            'pagina_web'    => 'nullable|url|max:200',

            'emails'              => 'nullable|array',
            'emails.*.id'         => 'nullable|integer|exists:email_empresas,id',
            'emails.*.area'       => 'required_with:emails|string|max:100',
            'emails.*.email'      => 'required_with:emails|email|max:150',
            'emails.*.activo'     => 'nullable|boolean',

            'telefonos'               => 'nullable|array',
            'telefonos.*.id'          => 'nullable|integer|exists:telefono_empresas,id',
            'telefonos.*.area'        => 'required_with:telefonos|string|max:100',
            'telefonos.*.telefono'    => 'required_with:telefonos|string|max:20',
            'telefonos.*.descripcion' => 'nullable|string|max:200',
            'telefonos.*.activo'      => 'nullable|boolean',
        ];

        // Las imágenes solo se incluyen en la validación al actualizar
        // (en creación los campos ni siquiera aparecen en el formulario)
        if ($isUpdate) {
            $rules['uri_img_logo']           = 'nullable|image|mimes:png,jpg,jpeg|max:2048';
            $rules['uri_img_publicidad']     = 'nullable|image|mimes:png,jpg,jpeg|max:2048';
            $rules['uri_img_condiciones']    = 'nullable|image|mimes:png,jpg,jpeg|max:2048';
            $rules['uri_cuentas_bancarias']  = 'nullable|image|mimes:png,jpg,jpeg|max:2048';
            $rules['eliminar_logo']          = 'nullable|boolean';
            $rules['eliminar_publicidad']    = 'nullable|boolean';
            $rules['eliminar_condiciones']   = 'nullable|boolean';
            $rules['eliminar_cuentas']       = 'nullable|boolean';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'razon_social.required'              => 'La razón social es obligatoria.',
            'ruc.digits'                         => 'El RUC debe tener exactamente 11 dígitos numéricos.',
            'pagina_web.url'                     => 'La página web debe ser una URL válida (ej: https://www.empresa.com).',
            'emails.*.area.required_with'        => 'El área del correo es obligatoria.',
            'emails.*.email.required_with'       => 'El correo electrónico es obligatorio.',
            'emails.*.email.email'               => 'El correo electrónico no tiene un formato válido.',
            'telefonos.*.area.required_with'     => 'El área del teléfono es obligatoria.',
            'telefonos.*.telefono.required_with' => 'El número de teléfono es obligatorio.',
            'uri_img_logo.image'                 => 'El logo debe ser una imagen.',
            'uri_img_logo.mimes'                 => 'El logo debe ser formato PNG, JPG o JPEG.',
            'uri_img_logo.max'                   => 'El logo no debe superar 2 MB.',
            'uri_img_publicidad.image'           => 'La imagen de publicidad debe ser una imagen.',
            'uri_img_publicidad.mimes'           => 'La imagen de publicidad debe ser formato PNG, JPG o JPEG.',
            'uri_img_publicidad.max'             => 'La imagen de publicidad no debe superar 2 MB.',
            'uri_img_condiciones.image'          => 'La imagen de condiciones debe ser una imagen.',
            'uri_img_condiciones.mimes'          => 'La imagen de condiciones debe ser formato PNG, JPG o JPEG.',
            'uri_img_condiciones.max'            => 'La imagen de condiciones no debe superar 2 MB.',
            'uri_cuentas_bancarias.image'        => 'La imagen de cuentas bancarias debe ser una imagen.',
            'uri_cuentas_bancarias.mimes'        => 'La imagen de cuentas bancarias debe ser formato PNG, JPG o JPEG.',
            'uri_cuentas_bancarias.max'          => 'La imagen de cuentas bancarias no debe superar 2 MB.',
        ];
    }
}
