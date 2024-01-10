<?php

namespace App\Http\Controllers\api\v2\Fees;

use App\Http\Controllers\Controller;
use App\Http\Resources\FmFeesInvoiceAddResource;
use App\Http\Resources\FmFeesInvoiceChieldViewResource;
use App\Http\Resources\FmFeesInvoiceResource;
use App\Http\Resources\FmFeesInvoiceViewResource;
use App\Models\StudentRecord;
use App\Models\User;
use App\SmAddIncome;
use App\SmBankAccount;
use App\SmClass;
use App\SmGeneralSettings;
use App\SmPaymentGatewaySetting;
use App\SmPaymentMethhod;
use App\SmSchool;
use App\SmStudent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CcAveune\Http\Controllers\CcAveuneController;
use Modules\Fees\Entities\FmFeesGroup;
use Modules\Fees\Entities\FmFeesInvoice;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Modules\Fees\Entities\FmFeesTransaction;
use Modules\Fees\Entities\FmFeesTransactionChield;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Http\Controllers\FeesExtendedController;
use Modules\Wallet\Entities\WalletTransaction;

class StudentFeesController extends Controller
{
    public function studentFeesList($record_id)
    {
        $user = auth()->user();
        if ($user->role_id != 2) {
            $response = [
                'status'  => false,
                'data' => 'No data found',
                'message' => 'Operation failed',
            ];
            return response()->json($response, 401);
        }
        $student_id = $user->student->id;

        $records = FmFeesInvoice::where('record_id', $record_id)
            ->where('student_id', $student_id)
            ->where('academic_id', getAcademicId())
            ->with('studentInfo', 'recordDetail.class', 'recordDetail.section')
            ->get();

        $data['fees_invoice'] = FmFeesInvoiceResource::collection($records);

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function addFeesPayment($fees_invoice_id)
    {
        // $data['classes'] = SmClass::where('school_id',Auth::user()->school_id)
        // ->where('academic_id',getAcademicId())
        // ->get();

        // $data['feesGroups'] = FmFeesGroup::where('school_id',Auth::user()->school_id)
        //             ->where('academic_id', getAcademicId())
        //             ->get();

        // $data['feesTypes'] = FmFeesType::where('school_id',Auth::user()->school_id)
        //             ->where('academic_id', getAcademicId())
        //             ->get();

        $paymentMethods = SmPaymentMethhod::select('method')->whereNotIn('method', ["Cash"])
            ->where('school_id', Auth::user()->school_id);

        if (!moduleStatusCheck('RazorPay')) {
            $paymentMethods = $paymentMethods->where('method', '!=', 'RazorPay');
        }


        $data['paymentMethods'] = $paymentMethods->get();


        $data['bankAccounts'] = SmBankAccount::select('id', 'bank_name', 'account_number')->where('school_id', Auth::user()->school_id)
            ->where('active_status', 1)
            ->where('academic_id', getAcademicId())
            ->get();
        $invoiceInfo = FmFeesInvoice::with('recordDetail', 'studentInfo')->find($fees_invoice_id);

        $data['invoiceInfo'] = new FmFeesInvoiceAddResource($invoiceInfo);

        $data['invoiceDetails'] = FmFeesInvoiceChield::select('fees_type', 'amount', 'due_amount', 'weaver', 'fine')->with(['feesType' => function ($q) {
            $q->select('id', 'name');
        }])->where('fees_invoice_id', $data['invoiceInfo']->id)
            ->where('school_id', Auth::user()->school_id)
            ->where('academic_id', getAcademicId())
            ->get();

        // $data['stripe_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')
        //                 ->where('school_id', Auth::user()->school_id)
        //                 ->first();
        // $data['razorpay_info'] = null;
        // if(moduleStatusCheck('RazorPay')){
        //     $data['razorpay_info'] = SmPaymentGatewaySetting::where('gateway_name', 'RazorPay')
        //         ->where('school_id', Auth::user()->school_id)
        //         ->first();
        // }

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function serviceCharge(Request $request)
    {
        $data['service_charge'] = serviceCharge($request->gateway);
        $data['service_charge_amount'] =  number_format(chargeAmount($request->gateway, $request->amount), 2, '.', '');

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function studentFeesPaymentStore(Request $request)
    {
        if ($request->total_paid_amount == null) {
            $response = [
                'status'  => false,
                'data' => 'Paid amount can not be blank',
                'message' => 'Paid amount can not be blank',
            ];
            return response()->json($response, 401);
        }

        $validator = Validator::make($request->all(), [
            'payment_method' =>  'required',
            'bank' =>  'required_if:payment_method,Bank',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => false,
                'data' => $validator->errors(),
                'message' => 'Operation failed',
            ];
            return response()->json($response, 401);
        }


        $destination = 'public/uploads/student/document/';
        $file = fileUpload($request->file('file'), $destination);

        $record = StudentRecord::find($request->student_id);
        $student = SmStudent::with('parents')->find($record->student_id);

        if ($request->payment_method == "Wallet") {
            $user = User::find(Auth::user()->id);
            if ($user->wallet_balance == 0) {
                $response = [
                    'status'  => false,
                    'data' => 'Insufficiant Balance',
                    'message' => 'Insufficiant Balance',
                ];
                return response()->json($response, 401);
            } elseif ($user->wallet_balance >= $request->total_paid_amount) {
                $user->wallet_balance = $user->wallet_balance - $request->total_paid_amount;
                $user->update();
            } else {
                $response = [
                    'status'  => false,
                    'data' => 'Total Amount Is Grater Than Wallet Amount',
                    'message' => 'Total Amount Is Grater Than Wallet Amount',
                ];
                return response()->json($response, 401);
            }
            $addPayment = new WalletTransaction();
            if ($request->add_wallet > 0) {
                $addAmount = $request->total_paid_amount - $request->add_wallet;
                $addPayment->amount = $addAmount;
            } else {
                $addPayment->amount = $request->total_paid_amount;
            }
            $addPayment->payment_method = $request->payment_method;
            $addPayment->user_id = $user->id;
            $addPayment->type = 'expense';
            $addPayment->status = 'approve';
            $addPayment->note = 'Fees Payment';
            $addPayment->school_id = Auth::user()->school_id;
            $addPayment->academic_id = getAcademicId();
            $addPayment->save();

            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $request->invoice_id;
            $storeTransaction->payment_note = $request->payment_note;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->add_wallet_money = $request->add_wallet;
            $storeTransaction->bank_id = $request->bank;
            $storeTransaction->student_id = $record->student_id;
            $storeTransaction->record_id = $record->id;
            $storeTransaction->user_id = Auth::user()->id;
            $storeTransaction->file = $file;
            $storeTransaction->paid_status = 'approve';
            $storeTransaction->school_id = Auth::user()->school_id;
            if (moduleStatusCheck('University')) {
                $storeTransaction->un_academic_id = getAcademicId();
            } else {
                $storeTransaction->academic_id = getAcademicId();
            }
            $storeTransaction->save();

            foreach ($request->fees_type as $key => $type) {
                $id = FmFeesInvoiceChield::where('fees_invoice_id', $request->invoice_id)->where('fees_type', $type)->first('id')->id;

                $storeFeesInvoiceChield = FmFeesInvoiceChield::find($id);
                $storeFeesInvoiceChield->due_amount = $request->due[$key];
                $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount + $request->paid_amount[$key] - $request->extraAmount[$key];
                $storeFeesInvoiceChield->update();

                if ($request->paid_amount[$key] > 0) {
                    $storeTransactionChield = new FmFeesTransactionChield();
                    $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                    $storeTransactionChield->fees_type = $type;
                    $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                    $storeTransactionChield->note = $request->note[$key];
                    $storeTransactionChield->school_id = Auth::user()->school_id;
                    if (moduleStatusCheck('University')) {
                        $storeTransactionChield->un_academic_id = getAcademicId();
                    } else {
                        $storeTransactionChield->academic_id = getAcademicId();
                    }
                    $storeTransactionChield->save();
                }
            }

            if ($request->add_wallet > 0) {
                $user->wallet_balance = $user->wallet_balance + $request->add_wallet;
                $user->update();

                $addPayment = new WalletTransaction();
                $addPayment->amount = $request->add_wallet;
                $addPayment->payment_method = $request->payment_method;
                $addPayment->user_id = $user->id;
                $addPayment->type = 'diposit';
                $addPayment->status = 'approve';
                $addPayment->note = 'Fees Extra Payment Add';
                $addPayment->school_id = Auth::user()->school_id;
                $addPayment->academic_id = getAcademicId();
                $addPayment->save();

                $school = SmSchool::find($user->school_id);
                $compact['full_name'] = $user->full_name;
                $compact['method'] = $request->payment_method;
                $compact['create_date'] = date('Y-m-d');
                $compact['school_name'] = $school->school_name;
                $compact['current_balance'] = $user->wallet_balance;
                $compact['add_balance'] = $request->add_wallet;
                $compact['previous_balance'] = $user->wallet_balance - $request->add_wallet;

                @send_mail($user->email, $user->full_name, "fees_extra_amount_add", $compact);
                sendNotification($user->id, null, null, $user->role_id, "Fees Xtra Amount Add");
            }

            // Income
            $payment_method = SmPaymentMethhod::where('method', $request->payment_method)->first();
            $income_head = generalSetting();

            $add_income = new SmAddIncome();
            $add_income->name = 'Fees Collect';
            $add_income->date = date('Y-m-d');
            $add_income->amount = $request->total_paid_amount;
            $add_income->fees_collection_id = $storeTransaction->id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $income_head->income_head_id;
            $add_income->payment_method_id = $payment_method->id;
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = Auth::user()->school_id;
            $add_income->academic_id = getAcademicId();
            $add_income->save();
        } elseif ($request->payment_method == "Cheque" || $request->payment_method == "Bank" || $request->payment_method == "MercadoPago") {
            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $request->invoice_id;
            $storeTransaction->payment_note = $request->payment_note;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->add_wallet_money = $request->add_wallet;
            $storeTransaction->bank_id = $request->bank;
            $storeTransaction->student_id = $record->student_id;
            $storeTransaction->record_id = $record->id;
            $storeTransaction->user_id = auth()->user()->id;
            $storeTransaction->file = $file;
            $storeTransaction->paid_status = 'pending';
            $storeTransaction->school_id = auth()->user()->school_id;
            if (moduleStatusCheck('University')) {
                $storeTransaction->un_academic_id = getAcademicId();
            } else {
                $storeTransaction->academic_id = getAcademicId();
            }
            $storeTransaction->save();

            foreach ($request->fees_type as $key => $type) {
                if ($request->paid_amount[$key] > 0) {
                    $storeTransactionChield = new FmFeesTransactionChield();
                    $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                    $storeTransactionChield->fees_type = $type;
                    $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                    $storeTransactionChield->service_charge = chargeAmount($request->payment_method, $request->paid_amount[$key]);
                    $storeTransactionChield->note = $request->note[$key];
                    $storeTransactionChield->school_id = auth()->user()->school_id;
                    if (moduleStatusCheck('University')) {
                        $storeTransactionChield->un_academic_id = getAcademicId();
                    } else {
                        $storeTransactionChield->academic_id = getAcademicId();
                    }
                    $storeTransactionChield->save();
                }
            }
            if (moduleStatusCheck('MercadoPago')) {
                if (@$request->payment_method == "MercadoPago") {
                    $storeTransaction->total_paid_amount = $request->total_paid_amount;
                    $storeTransaction->save();
                    return redirect()->route('mercadopago.mercadopago-fees-payment', ['traxId' => $storeTransaction->id]);
                }
            }
        } else {
            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $request->invoice_id;
            $storeTransaction->payment_note = $request->payment_note;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->student_id = $record->student_id;
            $storeTransaction->record_id = $record->id;
            $storeTransaction->add_wallet_money = $request->add_wallet;
            $storeTransaction->user_id = auth()->user()->id;
            $storeTransaction->paid_status = 'pending';
            $storeTransaction->school_id = auth()->user()->school_id;
            if (moduleStatusCheck('University')) {
                $storeTransaction->un_academic_id = getAcademicId();
            } else {
                $storeTransaction->academic_id = getAcademicId();
            }
            $storeTransaction->save();


            foreach ($request->fees_type as $key => $type) {
                if ($request->paid_amount[$key] > 0) {
                    $storeTransactionChield = new FmFeesTransactionChield();
                    $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                    $storeTransactionChield->fees_type = $type;
                    $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                    $storeTransactionChield->service_charge = chargeAmount($request->payment_method, $request->paid_amount[$key]);
                    $storeTransactionChield->note = $request->note[$key];
                    $storeTransactionChield->school_id = Auth::user()->school_id;
                    if (moduleStatusCheck('University')) {
                        $storeTransactionChield->un_academic_id = getAcademicId();
                    } else {
                        $storeTransactionChield->academic_id = getAcademicId();
                    }
                    $storeTransactionChield->save();
                }
            }

            $data = [];
            $data['invoice_id'] = $request->invoice_id;
            $data['amount'] = $request->total_paid_amount;
            $data['payment_method'] = $request->payment_method;
            $data['description'] = "Fees Payment";
            $data['type'] = "Fees";
            $data['student_id'] = $request->student_id;
            $data['user_id'] = $storeTransaction->user_id;
            $data['stripeToken'] = $request->stripeToken;
            $data['transcationId'] = $storeTransaction->id;
            $data['service_charge'] = chargeAmount($request->payment_method, $request->total_paid_amount);

            if ($data['payment_method'] == 'RazorPay') {
                $extendedController = new FeesExtendedController();
                $extendedController->addFeesAmount($storeTransaction->id, null);
            } elseif ($data['payment_method'] == 'CcAveune') {
                $ccAvenewPaymentController = new CcAveuneController();
                $ccAvenewPaymentController->studentFeesPay($data['amount'], $data['transcationId'], $data['type']);
            } else {
                $classMap = config('paymentGateway.' . $data['payment_method']);
                $make_payment = new $classMap();
                $url = $make_payment->handle($data);
                if (!$url) {
                    $url = url('fees/student-fees-list');
                    if (auth()->check() && auth()->user()->role_id == 3) {
                        $url = url('fees/student-fees-list', $record->student_id);
                    }
                }
                if ($request->wantsJson()) {
                    return response()->json(['goto' => $url]);
                } else {
                    return redirect($url);
                }
            }
        }

        //Notification
        sendNotification("Add Fees Payment", null, $student->user_id, 2);
        sendNotification("Add Fees Payment", null, $student->parents->user_id, 3);
        sendNotification("Add Fees Payment", null, 1, 1);

        // Toastr::success('Save Successful', 'Success');

        $response = [
            'success' => true,
            'data'    => 'Operation successful',
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function feesInvoiceView($fee_invoice_id)
    {
        $data['generalSetting'] = SmGeneralSettings::select('logo', 'school_name', 'phone', 'email', 'address')->where('school_id', Auth::user()->school_id)->first();
        $invoiceInfo = FmFeesInvoice::find($fee_invoice_id);
        $data['invoiceInfo'] = new FmFeesInvoiceViewResource($invoiceInfo);

        $invoiceDetails = FmFeesInvoiceChield::where('fees_invoice_id', $data['invoiceInfo']->id)
            ->where('school_id', Auth::user()->school_id)
            ->where('academic_id', getAcademicId())
            ->get();

        $data['invoiceDetails'] = FmFeesInvoiceChieldViewResource::collection($invoiceDetails);

        $data['banks'] = SmBankAccount::select('id', 'bank_name', 'account_name', 'account_number', 'account_type')->where('active_status', '=', 1)
            ->where('school_id', Auth::user()->school_id)
            ->get();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
        
    }
}
