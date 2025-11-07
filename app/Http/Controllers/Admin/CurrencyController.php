<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Models\Currency;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currencies = Currency::orderBy('id', 'desc')
                ->select('id', 'currency_code', 'current_currency', 'symbol', 'currency_placement');
            return datatables($currencies)
                ->addIndexColumn()
                ->editColumn('currency_code', function ($data) {
                    $currencyCode = $data->currency_code;
                    if ($data->current_currency == STATUS_ACTIVE) {
                        $currencyCode .= ' <b>('.__('Current Currency').')</b>';
                    }
                    return $currencyCode;
                })
                ->addColumn('action', function ($data) {
                    return '<ul class="d-flex align-items-center cg-5 justify-content-end">
                        <li class="align-items-center d-flex gap-2">
                            <button onclick="getEditModal(\'' . route('admin.setting.currencies.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo">
                                ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.setting.currencies.delete', $data->id) . '\', \'commonDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-white" title="Delete">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                        </li>
                    </ul>';
                })
                ->rawColumns(['action', 'currency_code'])
                ->make(true);
        }

        $data['pageTitle'] = __('Currency Setting');
        $data['activeSetting'] = 'active';
        $data['activeCurrenciesSetting'] = 'active';

        return view('admin.setting.currencies.index', $data);
    }

    public function edit($id)
    {
        $data['currency'] = Currency::findOrFail($id);
        return view('admin.setting.currencies.edit-form', $data);
    }

    public function store(CurrencyRequest $request)
    {
        DB::beginTransaction();
        try {
            $currency = new Currency();
            $currency->currency_code = $request->currency_code;
            $currency->symbol = $request->symbol;
            $currency->currency_placement = $request->currency_placement;
            $currency->save();

            if ($request->current_currency) {
                Currency::where('id', $currency->id)->update(['current_currency' => STATUS_ACTIVE]);
                Currency::where('id', '!=', $currency->id)->update(['current_currency' => STATUS_PENDING]);
            }

            DB::commit();

            $message = getMessage(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function update(CurrencyRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $currency = Currency::findOrFail($id);
            $currency->currency_code = $request->currency_code;
            $currency->symbol = $request->symbol;
            $currency->currency_placement = $request->currency_placement;
            $currency->save();

            if ($request->current_currency) {
                Currency::where('id', $currency->id)->update(['current_currency' => STATUS_ACTIVE]);
                Currency::where('id', '!=', $currency->id)->update(['current_currency' => STATUS_PENDING]);
            }

            DB::commit();

            $message = getMessage(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $currency = Currency::findOrFail($id);
            $currency->delete();

            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
