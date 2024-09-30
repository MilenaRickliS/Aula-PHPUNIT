<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders); 
    }

    
      public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $order = Order::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'final_price' =>0, 
        ]);

        
        $final_value = $order->quantity * $order->price;
        $order->final_price = $final_value;
        $order->save();

        return response()->json([
            'message' => 'Pedido registrado com sucesso. O valor total do pedido foi '.$final_value,
            'order' => $order,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            // Retorna um status 404 com uma mensagem de erro
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }
    
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            $order = Order::findOrFail($id);
            $order->update($request->all());
        
            return response()->json($order->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['msg' =>'Pedido deletado com sucesso!']);
    }
}
