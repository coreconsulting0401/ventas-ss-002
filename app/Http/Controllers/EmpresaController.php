<?php

//// app/Http/Controllers/EmpresaController.php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\EmailEmpresa;
use App\Models\TelefonoEmpresa;
use App\Http\Requests\EmpresaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmpresaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:Administrador'),
        ];
    }

    /**
     * Muestra la información de la empresa, o redirige según corresponda.
     * - Si no existe empresa → redirige a crear.
     * - Si existe empresa → muestra la vista index con sus datos.
     */
    public function index()
    {
        $empresa = Empresa::with(['emails', 'telefonos'])->first();

        if (!$empresa) {
            return redirect()->route('empresas.create')
                ->with('info', 'No hay empresa registrada. Complete el formulario para crear el registro.');
        }

        return view('empresas.index', compact('empresa'));
    }

    public function create()
    {
        if (Empresa::exists()) {
            return redirect()->route('empresas.index')
                ->with('info', 'Solo se permite un registro de empresa. Use la opción de editar.');
        }

        return view('empresas.create');
    }

    public function store(EmpresaRequest $request)
    {
        if (Empresa::exists()) {
            return back()->with('error', 'Ya existe un registro de empresa. Use la opción de editar.');
        }

        try {
            DB::beginTransaction();

            $data = $request->validated();

            $empresa = Empresa::create([
                'razon_social'          => $data['razon_social'],
                'ruc'                   => $data['ruc'] ?? null,
                'direccion'             => $data['direccion'] ?? null,
                'pagina_web'            => $data['pagina_web'] ?? null,
                'uri_img_logo'          => null,
                'uri_img_publicidad'    => null,
                'uri_img_condiciones'   => null,
                'uri_cuentas_bancarias' => null,
            ]);

            if (!empty($data['emails'])) {
                foreach ($data['emails'] as $email) {
                    if (!empty($email['area']) && !empty($email['email'])) {
                        $empresa->emails()->create([
                            'area'   => $email['area'],
                            'email'  => $email['email'],
                            'activo' => isset($email['activo']) ? (bool)$email['activo'] : true,
                        ]);
                    }
                }
            }

            if (!empty($data['telefonos'])) {
                foreach ($data['telefonos'] as $telefono) {
                    if (!empty($telefono['area']) && !empty($telefono['telefono'])) {
                        $empresa->telefonos()->create([
                            'area'        => $telefono['area'],
                            'telefono'    => $telefono['telefono'],
                            'descripcion' => $telefono['descripcion'] ?? null,
                            'activo'      => isset($telefono['activo']) ? (bool)$telefono['activo'] : true,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('empresas.edit', $empresa)
                ->with('success', 'Empresa creada exitosamente. Ahora puede cargar las imágenes.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la empresa: ' . $e->getMessage());
        }
    }

    public function edit(Empresa $empresa)
    {
        $empresa->load(['emails', 'telefonos']);
        return view('empresas.edit', compact('empresa'));
    }

    public function update(EmpresaRequest $request, Empresa $empresa)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $updateData = [
                'razon_social' => $data['razon_social'],
                'ruc'          => $data['ruc'] ?? null,
                'direccion'    => $data['direccion'] ?? null,
                'pagina_web'   => $data['pagina_web'] ?? null,
            ];

            // ── Imágenes: subir nueva o eliminar actual ────────────────────
            // Subir / reemplazar imágenes solo si se envía un archivo nuevo

            // Logo
            if ($request->hasFile('uri_img_logo')) {
                $this->deleteImage($empresa->uri_img_logo);
                $updateData['uri_img_logo'] = $this->uploadImage($request->file('uri_img_logo'), 'logos');
            } elseif ($request->boolean('eliminar_logo')) {
                $this->deleteImage($empresa->uri_img_logo);
                $updateData['uri_img_logo'] = null;
            }

            // Publicidad
            if ($request->hasFile('uri_img_publicidad')) {
                $this->deleteImage($empresa->uri_img_publicidad);
                $updateData['uri_img_publicidad'] = $this->uploadImage($request->file('uri_img_publicidad'), 'publicidad');
            } elseif ($request->boolean('eliminar_publicidad')) {
                $this->deleteImage($empresa->uri_img_publicidad);
                $updateData['uri_img_publicidad'] = null;
            }

            // Condiciones
            if ($request->hasFile('uri_img_condiciones')) {
                $this->deleteImage($empresa->uri_img_condiciones);
                $updateData['uri_img_condiciones'] = $this->uploadImage($request->file('uri_img_condiciones'), 'condiciones');
            } elseif ($request->boolean('eliminar_condiciones')) {
                $this->deleteImage($empresa->uri_img_condiciones);
                $updateData['uri_img_condiciones'] = null;
            }

            // Cuentas bancarias
            if ($request->hasFile('uri_cuentas_bancarias')) {
                $this->deleteImage($empresa->uri_cuentas_bancarias);
                $updateData['uri_cuentas_bancarias'] = $this->uploadImage($request->file('uri_cuentas_bancarias'), 'cuentas_bancarias');
            } elseif ($request->boolean('eliminar_cuentas')) {
                $this->deleteImage($empresa->uri_cuentas_bancarias);
                $updateData['uri_cuentas_bancarias'] = null;
            }

            $empresa->update($updateData);

            $this->syncEmails($empresa, $data['emails'] ?? []);
            $this->syncTelefonos($empresa, $data['telefonos'] ?? []);

            DB::commit();

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la empresa: ' . $e->getMessage());
        }
    }

    /**
     * Elimina la empresa (y en cascada sus emails, teléfonos e imágenes).
     * Después de eliminar redirige a crear.
     */
    public function destroy(Empresa $empresa)
    {
        try {
            $this->deleteImage($empresa->uri_img_logo);
            $this->deleteImage($empresa->uri_img_publicidad);
            $this->deleteImage($empresa->uri_img_condiciones);
            $this->deleteImage($empresa->uri_cuentas_bancarias);

            $empresa->delete();

            return redirect()->route('empresas.create')
                ->with('success', 'Empresa eliminada. Puede registrar una nueva empresa.');

        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar la empresa: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function uploadImage($file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $filename, 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Sincroniza la lista de emails del formulario con la BD.
     * - Actualiza los que ya tienen ID.
     * - Crea los nuevos.
     * - Elimina los que ya no están en el formulario.
     *
     * CORRECCIÓN: si la lista viene vacía se eliminan TODOS los emails existentes
     * (comportamiento esperado). Si se pasa null se mantienen los actuales.
     */
    private function syncEmails(Empresa $empresa, array $emails): void
    {
        $emailIds = [];

        foreach ($emails as $email) {
            if (empty($email['area']) || empty($email['email'])) {
                continue;
            }

            if (!empty($email['id'])) {
                $emailIds[] = $email['id'];
                $emailEmpresa = EmailEmpresa::where('id', $email['id'])
                    ->where('empresa_id', $empresa->id)
                    ->first();

                if ($emailEmpresa) {
                    $emailEmpresa->update([
                        'area'   => $email['area'],
                        'email'  => $email['email'],
                        'activo' => isset($email['activo']) ? (bool)$email['activo'] : true,
                    ]);
                }
            } else {
                $nuevo = $empresa->emails()->create([
                    'area'   => $email['area'],
                    'email'  => $email['email'],
                    'activo' => isset($email['activo']) ? (bool)$email['activo'] : true,
                ]);
                $emailIds[] = $nuevo->id;
            }
        }

        EmailEmpresa::where('empresa_id', $empresa->id)
            ->whereNotIn('id', $emailIds)
            ->delete();
    }

    /**
     * Sincroniza la lista de teléfonos del formulario con la BD.
     */
    private function syncTelefonos(Empresa $empresa, array $telefonos): void
    {
        $telefonoIds = [];

        foreach ($telefonos as $telefono) {
            if (empty($telefono['area']) || empty($telefono['telefono'])) {
                continue;
            }

            if (!empty($telefono['id'])) {
                $telefonoIds[] = $telefono['id'];
                $telefonoEmpresa = TelefonoEmpresa::where('id', $telefono['id'])
                    ->where('empresa_id', $empresa->id)
                    ->first();

                if ($telefonoEmpresa) {
                    $telefonoEmpresa->update([
                        'area'        => $telefono['area'],
                        'telefono'    => $telefono['telefono'],
                        'descripcion' => $telefono['descripcion'] ?? null,
                        'activo'      => isset($telefono['activo']) ? (bool)$telefono['activo'] : true,
                    ]);
                }
            } else {
                $nuevo = $empresa->telefonos()->create([
                    'area'        => $telefono['area'],
                    'telefono'    => $telefono['telefono'],
                    'descripcion' => $telefono['descripcion'] ?? null,
                    'activo'      => isset($telefono['activo']) ? (bool)$telefono['activo'] : true,
                ]);
                $telefonoIds[] = $nuevo->id;
            }
        }

        TelefonoEmpresa::where('empresa_id', $empresa->id)
            ->whereNotIn('id', $telefonoIds)
            ->delete();
    }
}
