<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class FirstTest extends Controller
{
    public function index(){
		$daftar = DB::table('tbpeserta')->get();
		return view('test',['daftar'=>$daftar]);
	}
}
