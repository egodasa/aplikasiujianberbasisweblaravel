<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class soalUjian extends Controller
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
    public function getSoalUjian(Request $req,$id){
		if(!empty($req->query('limit'))){
			$limit = $req->query('limit');
			$offset = $req->query('offset');
		}else{
			$limit = $offset = 0;
		}
		$data = new \stdClass;
		$data->status = true;
		if(!empty($req->query('belumDitambahkan'))){
			$data->data = DB::select('CALL getNotSoalUjian(?,?,?);',[$id,$limit,$offset]);
		}else{
			$data->data = DB::select('CALL getSoalUjian(?,?,?);',[$id,$limit,$offset]);
		}
		$data->row = DB::table('tbsoal_ujian')->count();
		return response()->json($data);
	}
	public function deleteSoalUjian($id,$idSU){
		$data = new \stdClass;
		$data->status = true;
		DB::select('CALL deleteSoalUjian("?");',[$idSU]);
		return response()->json($data);
	}
	public function addSoalUjian(Request $req,$id){
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
			DB::select('CALL createSoalUjian(?,@id);',[$id]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
