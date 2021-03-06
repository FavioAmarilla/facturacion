<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController as BaseController;
use App\Timbrado;

class TimbradoController extends BaseController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Timbrado::orderBy('fecha_desde', 'desc');

        $numero = $request->query('numero');
        if ($numero) {
            $query->where('numero', 'LIKE', '%'.$numero.'%');
        }

        $fecha_desde = $request->query('fecha_desde');
        if ($fecha_desde) {
            $query->where('fecha_desde', '=', $fecha_desde);
        }

        $fecha_hasta = $request->query('fecha_hasta');
        if ($fecha_hasta) {
            $query->where('fecha_hasta', '=', $fecha_hasta);
        }

        $numero_desde = $request->query('numero_desde');
        if ($numero_desde) {
            $query->where('numero_desde', 'LIKE', '%'.$numero_desde.'%');
        }

        $numero_hasta = $request->query('numero_hasta');
        if ($numero_hasta) {
            $query->where('numero_hasta', 'LIKE', '%'.$numero_hasta.'%');
        }


        $paginar = $request->query('paginar');
        $listar = (boolval($paginar)) ? 'paginate' : 'get';

        $data = $query->$listar();
        
        return $this->sendResponse(true, 'Listado obtenido exitosamente', $data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $numero = $request->input("numero");
        $fecha_desde = $request->input("fecha_desde");
        $fecha_hasta = $request->input("fecha_hasta");
        $numero_desde = $request->input("numero_desde");
        $numero_hasta = $request->input("numero_hasta");

        $validator = Validator::make($request->all(), [
            'numero'  => 'required',
            'fecha_desde'  => 'required',
            'fecha_hasta'  => 'required',
            'numero_desde'  => 'required',
            'numero_hasta'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }

        $timbrado = new Timbrado();
        $timbrado->numero = $numero;
        $timbrado->fecha_desde = $fecha_desde;
        $timbrado->fecha_hasta = $fecha_hasta;
        $timbrado->numero_desde = $numero_desde;
        $timbrado->numero_hasta = $numero_hasta;

        if ($timbrado->save()) {
            return $this->sendResponse(true, 'Timbrado registrado', $timbrado, 201);
        }
        
        return $this->sendResponse(false, 'Timbrado no registrado', null, 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $timbrado = Timbrado::find($id);

        if (is_object($timbrado)) {
            return $this->sendResponse(true, 'Se listaron exitosamente los registros', $timbrado, 200);
        }

        return $this->sendResponse(false, 'No se encontro el Timbrado', null, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $numero = $request->input("numero");
        $fecha_desde = $request->input("fecha_desde");
        $fecha_hasta = $request->input("fecha_hasta");
        $numero_desde = $request->input("numero_desde");
        $numero_hasta = $request->input("numero_hasta");

        $validator = Validator::make($request->all(), [
            'numero'  => 'required',
            'fecha_desde'  => 'required',
            'fecha_hasta'  => 'required',
            'numero_desde'  => 'required',
            'numero_hasta'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }

        $timbrado = Timbrado::find($id);
        if ($timbrado) {
            $timbrado->numero = $numero;
            $timbrado->fecha_desde = $fecha_desde;
            $timbrado->fecha_hasta = $fecha_hasta;
            $timbrado->numero_desde = $numero_desde;
            $timbrado->numero_hasta = $numero_hasta;
            
            if ($timbrado->save()) {
                return $this->sendResponse(true, 'Timbrado actualizado', $timbrado, 200);
            }
            
            return $this->sendResponse(false, 'Timbrado no actualizado', null, 400);
        }
        
        return $this->sendResponse(false, 'No se encontro la Timbrado', null, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function getTimbradoActivo()
    {
        $date = date('Y-m-d');

        $query = Timbrado::orderBy('created_at', 'desc');
        $query->where('fecha_desde', '<=', $date)->where('fecha_hasta', '>=', $date);
        $data = $query->first();

        return $this->sendResponse(true, 'Listado obtenido exitosamente', $data, 200);
    }

    public function updateUltUsado(Request $request, $id)
    {
        $ult_usado = $request->input("ult_usado");

        $validator = Validator::make($request->all(), [
            'ult_usado'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }

        $timbrado = Timbrado::find($id);
        if ($timbrado) {
            $timbrado->ult_usado = $ult_usado;
            
            if ($timbrado->save()) {
                return $this->sendResponse(true, 'Timbrado actualizado', $timbrado, 200);
            }
            
            return $this->sendResponse(false, 'Timbrado no actualizado', null, 400);
        }
        
        return $this->sendResponse(false, 'No se encontro la Timbrado', null, 404);
    }

}
