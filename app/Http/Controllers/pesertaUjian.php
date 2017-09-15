<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class pesertaUjian extends Controller
{
    public function getPesertaUjian(Request $req,$id = null,$idPeserta = null){
		$data = new \stdClass;
		$data->status = true;
		$limit = $req->query('limit');
		$offset = $req->query('offset');
		$db = DB::table('tbpeserta_ujian')->where('id_ujian',$id)->limit($limit)->offset($offset);
		if($req->query('belumDitambahkan') == null){
			$data->data = $db->select('tbpeserta_ujian.id','tbpeserta_ujian.id_peserta','tbpeserta.nm_peserta')->leftJoin('tbpeserta','tbpeserta_ujian.id_peserta','=','tbpeserta.id_peserta')->get();
		}else{
			$notIn = $db->where('id_ujian',$id)->pluck('id_peserta');
			$data->data = DB::table('tbpeserta')->whereNotIn('id_peserta',$notIn)->get();
		}
		$data->row = DB::table('tbpeserta_ujian')->where('id_ujian',$id)->count();
		$data->current_row = count($data->data);
		return response()->json($data);
	}
	public function addPesertaUjian(Request $req,$id){
		$data = new \stdClass;
		$data->status = true;
		DB::table('tbpeserta_ujian')->insert([
			'id_ujian'=>$id,
			'id_peserta'=>$req->id_peserta
		]);
		return response()->json($data);
	}
	public function deletePesertaUjian($id){
		$data = new \stdClass;
		$data->status = true;
		DB::table('tbpeserta_ujian')->where('id',$id)->delete();
		return response()->json($data);
	}
}
