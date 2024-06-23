<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\User as ModelsUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BankController extends Controller
{
    public function indexWithdrawal(){
        try {
            $user = ModelsUser::find(Auth::user()->id);
            return Inertia::render('Bank/IndexWithdrawal', ['saldoU' => number_format($user->saldo,0,'.',',')]);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function indexDeposit(){
        try {
            $user = ModelsUser::find(Auth::user()->id);
            return Inertia::render('Bank/IndexDeposit', ['saldoU' => number_format($user->saldo,0,'.',',')]);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function storeWithdrawal(Request $request){
        try {
            $user = ModelsUser::find(Auth::user()->id);
            if($user->saldo >= $request->cantidad){
                $user->saldo = $user->saldo - $request->cantidad;
                $user->save();

                $billetes   = self::getBilletes($request->cantidad);
                $validate   = true;
                $mensaje    = "Retiro exitoso";
            }else{
                $billetes   = "";
                $validate   = false;
                $mensaje    = "Fondos insuficientes";
            }

            self::saveHistorial($request->cantidad, strtolower($mensaje));

            return Inertia::render('Bank/IndexWithdrawal', ['saldoU' => number_format($user->saldo,0,'.',','), 'validate' => $validate, 'mensaje' => $mensaje, 'billetes' => $billetes]);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function storeDeposit(Request $request){
        try {
            $user = ModelsUser::find(Auth::user()->id);
            $user->saldo = $user->saldo + $request->cantidad;
            $user->save();

            self::saveHistorial($request->cantidad, 'consignacion');

            return to_route('bank.indexDeposit');
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    private function getBilletes($cantidad){
        try {
            $listBilletes = array(50000,20000,10000,5000,1000);
            $number = 0;
            $msm = '';

            foreach ($listBilletes as $billete) {
                if($cantidad >= $billete){
                    $number     = intval($cantidad/$billete);
                    $cantidad   = $cantidad % $billete;

                    $msm .= $number . ' billete' . (($number > 1 ) ? 's':'') . ' de $' . number_format($billete,0,'.',',') . '<br>';
                }
            }

            return $msm;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    private function saveHistorial($cantidad, $estado){
        try {
            $historial = new Historial();
            $historial->id_user = Auth::user()->id;
            $historial->fecha = date('Y-m-d H:i:s');
            $historial->cantidad = $cantidad;
            $historial->estado = $estado;

            $historial->save();
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
}
