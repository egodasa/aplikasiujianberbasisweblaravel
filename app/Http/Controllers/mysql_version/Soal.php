<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Soal extends Controller
{
    public $cekSoal = [
		'isi_soal'=>'bail|required|max:750|min:3',
		'jawaban'=>'bail|required|max:5'
	];
	public $errSoal = [
		'isi_soal.required'=>'Isi soal tidak boleh kosong!',
		'isi_soal.max'=>'Isi soal terlalu panjang!',
		'jawaban.required'=>'Jawaban harus dipilih!',
		'jawaban.max'=>'Jawaban terlalu panjang!'
	];
    public function __construct(){
		
	}
    public function getSoal(Request $req,$id = null){
		if(!empty($req->query('limit'))){
			$limit = $req->query('limit');
			$offset = $req->query('offset');
		}else{
			$limit = $offset = 0;
		}
		$data = new \stdClass;
		if($id == null){
			$data->data = DB::select('CALL getSoal("0000000",?,?);',[$limit,$offset]);
		}else{
			$data->data = DB::select('CALL getSoal(?,0,0);',[$id]);
			$data->data[0]->pilihanGanda = DB::table('tbpilihan_ganda')->where('id_soal',$id)->get();
		}
		$data->row = DB::table('tbsoal')->count();
		return response()->json($data);
	}
	public function getSoalPagination($limit,$offset){
		$data = new \stdClass;
		$data->data = DB::select('CALL getSoal("0000000",?,?);',[$limit,$offset]);
		$data->row = count($data->data);
		return response()->json($data);
	}
	public function addSoal(Request $req){
		$data = new \stdClass;
		$validator = \Validator::make($req->all(), $this->cekSoal, $this->errSoal);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$sqlPilihanGanda = "";
			$x=0;
			foreach($req->pilihanGanda as $pg){
				if($x == 0){
					$sqlPilihanGanda = "(@id,'".$pg['huruf']."','".$pg['isi_pilihan']."')";
				}else{
					$sqlPilihanGanda .= ",(@id,'".$pg['huruf']."','".$pg['isi_pilihan']."')";
				}
				$x++;
			}
			DB::statement(DB::raw('SET @id=genIdSoal();'));
			DB::select('CALL createSoal(@id,"'.$req->isi_soal.'","'.$req->jawaban.'");');
			DB::select('INSERT INTO tbpilihan_ganda(id_soal,huruf,isi_pilihan) VALUES '.$sqlPilihanGanda.';');
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
	public function deleteSoal($id){
		DB::select('call deleteSoal(?);',[$id]);
		return response()->json(['status'=>true]);
	}
	public function updateSoal(Request $req,$id){
		$data = new \stdClass;
		$validator = \Validator::make($req->all(), $this->cekSoal, $this->errSoal);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$sqlPilihanGanda = "";
			$x=0;
			foreach($req->pilihanGanda as $pg){
				if($x == 0){
					$sqlPilihanGanda = "('".$id."','".$pg['huruf']."','".$pg['isi_pilihan']."')";
				}else{
					$sqlPilihanGanda .= ",('".$id."','".$pg['huruf']."','".$pg['isi_pilihan']."')";
				}
				$x++;
			}
			DB::select('CALL updateSoal(?,?,?);',[$id,$req->isi_soal,$req->jawaban]);
			DB::table('tbpilihan_ganda')->where('id_soal',$id)->delete();
			DB::select('INSERT INTO tbpilihan_ganda(id_soal,huruf,isi_pilihan) VALUES '.$sqlPilihanGanda.';');
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
