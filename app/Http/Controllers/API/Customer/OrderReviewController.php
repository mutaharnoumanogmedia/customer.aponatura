<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrderReviewController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'customer_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new order review
        if (OrderReview::where('order_id', $request->order_id)
            ->where('customer_id', $request->customer_id)->exists()
        ) {
            return response()->json([
                'status' => 'success',
                'message' => 'You have already reviewed this order.',
            ], 201);
        }
        $orderReview = new OrderReview();
        $orderReview->order_id = $request->order_id;
        $orderReview->customer_id = $request->customer_id;
        $orderReview->rating = $request->rating;
        $orderReview->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order review submitted successfully.',
            'data' => $orderReview,
        ], 201);
    }
}
