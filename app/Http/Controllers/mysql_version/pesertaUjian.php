<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class pesertaUjian extends Controller
{
    public function getPesertaUjian(Request $req,$id,$idPeserta = 0){
		$data = new \stdClass;
		$data->status = true;
		if(!empty($req->query('limit'))){
			$limit = $req->query('limit');
			$offset = $req->query('offset');
		}else{
			$limit = $offset = 0;
		}
		$data->status = true;
		if(!empty($req->query('belumDitambahkan'))){
			$data->data = DB::select('CALL getNotPesertaUjian(?,?,?);',[$id,$limit,$offset]);
		}else{
			$data->data = DB::select('CALL getPesertaUjian(?,?,?,?);',[$id,$idPeserta,$limit,$offset]);
		}
		$data->row = DB::table('tbpeserta_ujian')->count();;
		return response()->json($data);
	}
	public function addPesertaUjian(Request $req,$id){
		$data = new \stdClass;
		$data->status = true;
		DB::select('CALL createPesertaUjian(?,?);',[$id,$req->id_peserta]);
		return response()->json($data);
	}
	public function deletePesertaUjian($id,$idPU){
		$data = new \stdClass;
		$data->status = true;
		DB::select('CALL deletePesertaUjian(?);',[$idPU]);
		return response()->json($data);
	}
}
