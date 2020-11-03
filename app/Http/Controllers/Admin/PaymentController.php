<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        return view('admin.payment.index');
    }

    /**
     * [create description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
        return view('admin.payment.create');
    }

    /**
     * [document description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function document(Request $request)
    {
        return view('admin.payment.document');
    }

    /**
     * [invoice description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function invoice(Request $request)
    {
        return view('admin.payment.invoice');
    }

    /**
     * [details description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function details(Request $request)
    {
        return view('admin.payment.details');
    }

    /**
     * [edit description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
        return view('admin.payment.edit');
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        return view('admin.payment.index');
    }

    /**
     * [destroy description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request)
    {
        return view('admin.payment.index');
    }
}
